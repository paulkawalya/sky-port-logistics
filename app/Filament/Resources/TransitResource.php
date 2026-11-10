<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransitResource\Pages;
use App\Filament\Resources\TransitResource\RelationManagers;
use App\Models\ImportShipment;
use App\Models\Transit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class TransitResource extends Resource
{
    protected static ?string $model = Transit::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Operations & Freight';

    protected static ?string $navigationLabel = 'Containers & Transits';

    protected static ?string $modelLabel = 'Transit / Container';

    protected static ?string $pluralModelLabel = 'Containers & Transits';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Container / Transit Info')
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->label('Container / Air Number')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->options([
                                'Normal' => 'Normal',
                                'Mixed' => 'Mixed',
                                'FCL' => 'FCL',
                                'Kitagululo' => 'Kitagululo',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('size')
                            ->label('Container Size (e.g. 20ft, 40ft, 40HQ)')
                            ->maxLength(255),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'not paid' => 'Not Paid',
                                'partialy paid' => 'Partially Paid',
                                'paid' => 'Paid',
                            ])
                            ->default('not paid')
                            ->required(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                    ])->columns(['default' => 1, 'md' => 2]),

                Forms\Components\Section::make('Route & Customs Information')
                    ->schema([
                        Forms\Components\TextInput::make('departure_place')
                            ->label('Port / Place of Departure')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('destination_place')
                            ->label('Port / Destination')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('expected_arrival_time')
                            ->label('Expected Arrival (ETA)'),
                        Forms\Components\TextInput::make('entry_number')
                            ->label('Customs Entry Number')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('clearing_agent')
                            ->label('Clearing Agent')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('contact_information')
                            ->label('Contact / Tracking Notes')
                            ->maxLength(255),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Container & Transit File')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('number')
                            ->label('Container / Flight #')
                            ->weight('bold')
                            ->badge()
                            ->color('info'),
                        \Filament\Infolists\Components\TextEntry::make('category')
                            ->label('Category')
                            ->badge(),
                        \Filament\Infolists\Components\TextEntry::make('size')
                            ->label('Container Size')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->colors([
                                'danger' => 'not paid',
                                'warning' => 'partialy paid',
                                'success' => 'paid',
                            ]),
                        \Filament\Infolists\Components\TextEntry::make('departure_place')
                            ->label('Port / Origin')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('destination_place')
                            ->label('Destination')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('expected_arrival_time')
                            ->label('Expected Arrival (ETA)')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('clearing_agent')
                            ->label('Clearing Agent')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('entry_number')
                            ->label('Customs Entry Number')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('contact_information')
                            ->label('Tracking Notes')
                            ->placeholder('None'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Container Number')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'FCL' => 'success',
                        'Mixed' => 'warning',
                        'Kitagululo' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('departure_place')
                    ->label('Origin')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('destination_place')
                    ->label('Destination')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expected_arrival_time')
                    ->label('ETA')
                    ->sortable(),
                Tables\Columns\TextColumn::make('clearing_agent')
                    ->label('Clearing Agent')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->colors([
                        'danger' => 'not paid',
                        'warning' => 'partialy paid',
                        'success' => 'paid',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Normal' => 'Normal',
                        'Mixed' => 'Mixed',
                        'FCL' => 'FCL',
                        'Kitagululo' => 'Kitagululo',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'not paid' => 'Not Paid',
                        'partialy paid' => 'Partially Paid',
                        'paid' => 'Paid',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('importStuffing')
                    ->label('Import Stuffing')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->modalHeading(fn (Transit $record) => "Import Stuffing Report: Container #{$record->number}")
                    ->modalDescription('Upload an Excel spreadsheet to bulk-create consignments, customers, and auto-generate freight invoices with Freight, BL, and other line items.')
                    ->form([
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
                    ->action(function (Transit $record, array $data): void {
                        $filePath = Storage::disk('local')->path($data['file']);

                        try {
                            $importer = new ImportShipment($record);
                            Excel::import($importer, $filePath);
                            Storage::disk('local')->delete($data['file']);

                            Notification::make()
                                ->title('Stuffing Report Imported')
                                ->body("Successfully imported {$importer->importedCount} consignments and auto-generated {$importer->invoicesCount} freight invoices for Container #{$record->number}.")
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
                Tables\Actions\Action::make('markAsPaid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Transit $record) => $record->payment_status !== 'paid')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Transit $record) => "Mark Container #{$record->number} as Paid")
                    ->modalDescription('Confirm that all transit shipping charges for this container have been fully settled.')
                    ->action(function (Transit $record): void {
                        $record->payment_status = 'paid';
                        $record->save();

                        Notification::make()
                            ->title('Container Marked as Paid')
                            ->body("Container #{$record->number} payment status updated to Paid.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\EditAction::make(),
            ])
            ->striped()
            ->emptyStateHeading('No Containers or Transits Recorded')
            ->emptyStateDescription('Create a container transit file to track origin, destination, ETA, and consolidate consignments.')
            ->emptyStateIcon('heroicon-o-cube-transparent')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ShippmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransits::route('/'),
            'create' => Pages\CreateTransit::route('/create'),
            'edit' => Pages\EditTransit::route('/{record}/edit'),
        ];
    }
}
