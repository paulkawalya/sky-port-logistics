<?php

namespace App\Filament\Resources\ShippmentResource\Pages;

use App\Filament\Resources\ShippmentResource;
use App\Filament\Resources\ShippmentResource\Widgets\ShippmentOverviewWidget;
use App\Models\ImportShipment;
use App\Models\Shippment;
use App\Models\Transit;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class ListShippments extends ListRecords
{
    protected static string $resource = ShippmentResource::class;

    public ?string $activeTab = 'all';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('importStuffing')
                ->label('Import Stuffing Report')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Import Container Stuffing Report')
                ->modalDescription('Upload an Excel spreadsheet to bulk-create consignments, customers, and auto-generate freight invoices with Freight, BL, and other line items.')
                ->form([
                    Forms\Components\Select::make('transit_id')
                        ->label('Target Container / Transit')
                        ->options(Transit::orderByDesc('id')->pluck('number', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Placeholder::make('template_info')
                        ->label('Standard Template')
                        ->content(new HtmlString('
                            <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm flex items-center justify-between border border-gray-200 dark:border-gray-700">
                                <div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Need the standardized format?</span>
                                    <p class="text-xs text-gray-500">Download the Sifcargo container stuffing template before uploading.</p>
                                </div>
                                <a href="/files/tamplate.xlsx" download class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-red-600 text-white hover:bg-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download Template
                                </a>
                            </div>
                        ')),
                    Forms\Components\FileUpload::make('file')
                        ->label('Stuffing Report File (.xlsx, .xls, .csv)')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $transit = Transit::findOrFail($data['transit_id']);
                    $filePath = Storage::disk('local')->path($data['file']);

                    try {
                        $importer = new ImportShipment($transit);
                        Excel::import($importer, $filePath);
                        Storage::disk('local')->delete($data['file']);

                        Notification::make()
                            ->title('Stuffing Report Imported')
                            ->body("Successfully imported {$importer->importedCount} consignments and auto-generated {$importer->invoicesCount} freight invoices for Container #{$transit->number}.")
                            ->success()
                            ->duration(8000)
                            ->send();
                    } catch (\Throwable $e) {
                        Storage::disk('local')->delete($data['file']);
                        Notification::make()
                            ->title('Import Failed')
                            ->body('Error importing file: ' . $e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
            Actions\CreateAction::make()
                ->label('New Consignment')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ShippmentOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Consignments'),
            'in_transit' => Tab::make('In Transit')
                ->badge(fn () => Shippment::where('cargo_status', 'In Transit')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'In Transit')),
            'in_store' => Tab::make('In Store')
                ->badge(fn () => Shippment::where('cargo_status', 'Received in Store')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Received in Store')),
            'ready' => Tab::make('Ready for Pickup')
                ->badge(fn () => Shippment::where('cargo_status', 'Ready for Pickup')->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Ready for Pickup')),
            'delivered' => Tab::make('Delivered')
                ->badge(fn () => Shippment::where('cargo_status', 'Delivered')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('cargo_status', 'Delivered')),
        ];
    }
}
