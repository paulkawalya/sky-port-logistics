<?php

namespace App\Filament\Resources\TransitResource\RelationManagers;

use App\Models\ImportShipment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Shippment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class ShippmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shippments';

    protected static ?string $recordTitleAttribute = 'mark';

    protected static ?string $title = 'Consignments & Auto-Invoices in this Container';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('shipper')
                    ->label('Shipper / Supplier')
                    ->maxLength(255),
                Forms\Components\TextInput::make('mark')
                    ->label('Shipping Mark')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_of_packages')
                    ->label('Packages')
                    ->numeric(),
                Forms\Components\TextInput::make('weight')
                    ->label('Weight (KG)')
                    ->numeric(),
                Forms\Components\TextInput::make('cbm')
                    ->label('CBM (m³)')
                    ->numeric(),
                Forms\Components\Textarea::make('description')
                    ->label('Cargo Description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mark')
                    ->label('Shipping Mark')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Consignee / Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_packages')
                    ->label('Packages')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight (KG)')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cbm')
                    ->label('CBM')
                    ->numeric(3)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo_status')
                    ->label('Store Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'In Transit' => 'info',
                        'Received in Store', 'Sorted & Inspected' => 'warning',
                        'Ready for Pickup' => 'primary',
                        'Dispatched', 'Delivered' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('invoice_status')
                    ->label('Freight Invoice')
                    ->state(function (Shippment $record): string {
                        $invoices = $record->invoices;
                        if ($invoices->isEmpty()) {
                            return 'No Invoice';
                        }
                        $first = $invoices->first();
                        if ($first->balance <= 0 && $first->total > 0) {
                            return 'Paid ($' . number_format($first->total, 2) . ')';
                        }
                        if ($first->paid > 0) {
                            return 'Partial ($' . number_format($first->balance, 2) . ' due)';
                        }
                        return 'Unpaid ($' . number_format($first->total, 2) . ')';
                    })
                    ->badge()
                    ->color(function (Shippment $record): string {
                        $invoices = $record->invoices;
                        if ($invoices->isEmpty()) {
                            return 'danger';
                        }
                        $first = $invoices->first();
                        if ($first->balance <= 0 && $first->total > 0) {
                            return 'success';
                        }
                        if ($first->paid > 0) {
                            return 'warning';
                        }
                        return 'danger';
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Consignment')
                    ->icon('heroicon-o-plus-circle'),
                Tables\Actions\Action::make('importStuffing')
                    ->label('Import Stuffing Report')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->modalHeading('Import Container Stuffing Report')
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
                    ->action(function (array $data): void {
                        $transit = $this->getOwnerRecord();
                        $filePath = Storage::disk('local')->path($data['file']);

                        try {
                            $importer = new ImportShipment($transit);
                            Excel::import($importer, $filePath);
                            Storage::disk('local')->delete($data['file']);

                            Notification::make()
                                ->title('Stuffing Report Imported')
                                ->body("Processed stuffing report: {$importer->importedCount} consignments recorded and {$importer->invoicesCount} freight invoices auto-generated.")
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
            ])
            ->actions([
                Tables\Actions\Action::make('createInvoice')
                    ->label('Generate Invoice')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->color('info')
                    ->visible(fn (Shippment $record) => $record->invoices->isEmpty())
                    ->modalHeading(fn (Shippment $record) => 'Generate Freight Invoice: ' . $record->mark)
                    ->form([
                        Forms\Components\TextInput::make('freight')
                            ->label('Ocean / Air Freight ($)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('bl_fee')
                            ->label('Bill of Lading (BL) Fee ($)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('others')
                            ->label('Other Port / Clearance Charges ($)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->action(function (Shippment $record, array $data): void {
                        $invoice = Invoice::saveInvoice($record->id);
                        if ($invoice) {
                            $freight = (float)($data['freight'] ?? 0);
                            $bl = (float)($data['bl_fee'] ?? 0);
                            $others = (float)($data['others'] ?? 0);

                            InvoiceDetail::saveDetails($invoice->id, 'Freight', 1, $freight);
                            InvoiceDetail::saveDetails($invoice->id, 'Bill of Lading', 1, $bl);
                            InvoiceDetail::saveDetails($invoice->id, 'Others', 1, $others);

                            Notification::make()
                                ->title('Freight Invoice Generated')
                                ->body("Invoice #{$invoice->id} created with total $" . number_format($freight + $bl + $others, 2))
                                ->success()
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
