<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippmentResource\Pages;
use App\Filament\Resources\ShippmentResource\RelationManagers;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Shippment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ShippmentResource extends Resource
{
    protected static ?string $model = Shippment::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Operations & Freight';

    protected static ?string $navigationLabel = 'Shipments';

    protected static ?string $modelLabel = 'Shipment';

    protected static ?string $pluralModelLabel = 'Shipments';

    protected static ?string $recordTitleAttribute = 'mark';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Shipment Details')
                    ->schema([
                        Forms\Components\Select::make('transit_id')
                            ->relationship('transit', 'number')
                            ->searchable()
                            ->preload()
                            ->label('Transit / Container Number')
                            ->required(),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Customer')
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Agent')
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                        Forms\Components\TextInput::make('shipper')
                            ->label('Shipper / Supplier')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('mark')
                            ->label('Shipping Mark')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('type')
                            ->label('Cargo Type (e.g. FCL, LCL, Air)')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Measurements & Cargo Specs')
                    ->schema([
                        Forms\Components\TextInput::make('number_of_packages')
                            ->label('Number of Packages')
                            ->numeric(),
                        Forms\Components\TextInput::make('bl_packages')
                            ->label('B/L Packages'),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (KG)')
                            ->numeric(),
                        Forms\Components\TextInput::make('cbm')
                            ->label('CBM (m³)')
                            ->numeric(),
                        Forms\Components\TextInput::make('volume')
                            ->label('Volume'),
                        Forms\Components\TextInput::make('unit_of_measure')
                            ->label('Unit of Measure')
                            ->default('PCS'),
                        Forms\Components\Textarea::make('description')
                            ->label('Cargo Description')
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Store & Warehouse Tracking')
                    ->description('Track physical cargo status inside the warehouse')
                    ->schema([
                        Forms\Components\Select::make('cargo_status')
                            ->label('Warehouse / Store Status')
                            ->options([
                                'In Transit' => 'In Transit',
                                'Received in Store' => 'Received in Store',
                                'Sorted & Inspected' => 'Sorted & Inspected',
                                'Ready for Pickup' => 'Ready for Pickup',
                                'Dispatched' => 'Dispatched',
                                'Delivered' => 'Delivered to Customer',
                            ])
                            ->default('In Transit')
                            ->required(),
                        Forms\Components\TextInput::make('warehouse_location')
                            ->label('Store Location / Shelf / Bay')
                            ->placeholder('e.g. Bay 4 - Shelf B2, Kampala Warehouse')
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('received_in_store_at')
                            ->label('Received in Store Time'),
                        Forms\Components\DateTimePicker::make('dispatched_at')
                            ->label('Dispatched / Delivered Time'),
                        Forms\Components\TextInput::make('receiver_name')
                            ->label('Receiver Person Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('receiver_phone')
                            ->label('Receiver Contact Phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Freight Invoicing (Optional)')
                    ->description('Auto-generate freight invoice immediately for this consignment')
                    ->schema([
                        Forms\Components\Toggle::make('auto_generate_invoice')
                            ->label('Auto-Generate Freight Invoice')
                            ->reactive()
                            ->default(false),
                        Forms\Components\TextInput::make('invoice_freight')
                            ->label('Ocean / Air Freight ($)')
                            ->numeric()
                            ->default(0)
                            ->visible(fn ($get) => (bool)$get('auto_generate_invoice')),
                        Forms\Components\TextInput::make('invoice_bl_fee')
                            ->label('Bill of Lading (BL) Fee ($)')
                            ->numeric()
                            ->default(0)
                            ->visible(fn ($get) => (bool)$get('auto_generate_invoice')),
                        Forms\Components\TextInput::make('invoice_others')
                            ->label('Other Port / Clearance Charges ($)')
                            ->numeric()
                            ->default(0)
                            ->visible(fn ($get) => (bool)$get('auto_generate_invoice')),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(fn (string $operation) => $operation === 'edit'),
            ]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Cargo & Consignment Overview')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('transit.number')
                            ->label('Container / Transit #')
                            ->badge()
                            ->color('info'),
                        \Filament\Infolists\Components\TextEntry::make('customer.name')
                            ->label('Client / Consignee')
                            ->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('customer.phone_number')
                            ->label('Client Phone'),
                        \Filament\Infolists\Components\TextEntry::make('agent.name')
                            ->label('Clearing Agent'),
                        \Filament\Infolists\Components\TextEntry::make('mark')
                            ->label('Shipping Mark')
                            ->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('shipper')
                            ->label('Shipper / Supplier')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('type')
                            ->label('Cargo Type')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('number_of_packages')
                            ->label('Packages')
                            ->badge()
                            ->color('primary'),
                        \Filament\Infolists\Components\TextEntry::make('weight')
                            ->label('Weight')
                            ->suffix(' KG'),
                        \Filament\Infolists\Components\TextEntry::make('cbm')
                            ->label('CBM')
                            ->suffix(' m³'),
                    ])->columns(2),

                \Filament\Infolists\Components\Section::make('Warehouse & Store Status')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('cargo_status')
                            ->label('Store Status')
                            ->badge()
                            ->colors([
                                'gray' => 'In Transit',
                                'warning' => 'Received in Store',
                                'info' => 'Sorted & Inspected',
                                'primary' => 'Ready for Pickup',
                                'success' => 'Delivered',
                            ]),
                        \Filament\Infolists\Components\TextEntry::make('warehouse_location')
                            ->label('Store Shelf / Location')
                            ->placeholder('Not assigned yet'),
                        \Filament\Infolists\Components\TextEntry::make('received_in_store_at')
                            ->label('Received in Store Time')
                            ->dateTime()
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('dispatched_at')
                            ->label('Dispatched / Release Time')
                            ->dateTime()
                            ->placeholder('Not dispatched'),
                        \Filament\Infolists\Components\TextEntry::make('receiver_name')
                            ->label('Received By Person')
                            ->placeholder('Pending pickup'),
                        \Filament\Infolists\Components\TextEntry::make('receiver_phone')
                            ->label('Receiver Phone')
                            ->placeholder('Pending pickup'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transit.number')
                    ->label('Container/Transit')
                    ->badge()
                    ->color('info')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mark')
                    ->label('Mark')
                    ->placeholder('N/A')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo_status')
                    ->label('Store Status')
                    ->badge()
                    ->colors([
                        'gray' => 'In Transit',
                        'warning' => 'Received in Store',
                        'info' => 'Sorted & Inspected',
                        'primary' => 'Ready for Pickup',
                        'success' => 'Delivered',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouse_location')
                    ->label('Shelf/Location')
                    ->placeholder('Unassigned')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receiver_name')
                    ->label('Receiver')
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('number_of_packages')
                    ->label('Pkgs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight')
                    ->suffix(' KG')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cbm')
                    ->label('CBM')
                    ->sortable(),
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
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cargo_status')
                    ->options([
                        'In Transit' => 'In Transit',
                        'Received in Store' => 'Received in Store',
                        'Sorted & Inspected' => 'Sorted & Inspected',
                        'Ready for Pickup' => 'Ready for Pickup',
                        'Dispatched' => 'Dispatched',
                        'Delivered' => 'Delivered',
                    ])
                    ->label('Filter by Store Status'),
                Tables\Filters\SelectFilter::make('transit_id')
                    ->relationship('transit', 'number')
                    ->label('Filter by Container'),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Filter by Customer'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\Action::make('receiveInStore')
                    ->label('Receive in Store')
                    ->icon('heroicon-o-arrow-down-on-square')
                    ->color('warning')
                    ->visible(fn ($record) => $record->cargo_status === 'In Transit')
                    ->action(fn ($record) => $record->update([
                        'cargo_status' => 'Received in Store',
                        'received_in_store_at' => now(),
                    ])),
                Tables\Actions\Action::make('markReady')
                    ->label('Ready for Pickup')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->visible(fn ($record) => in_array($record->cargo_status, ['Received in Store', 'Sorted & Inspected']))
                    ->action(fn ($record) => $record->update([
                        'cargo_status' => 'Ready for Pickup',
                    ])),
                Tables\Actions\Action::make('markDelivered')
                    ->label('Checkout & Release')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->slideOver()
                    ->visible(fn ($record) => in_array($record->cargo_status, ['Received in Store', 'Sorted & Inspected', 'Ready for Pickup']))
                    ->form(function (Shippment $record) {
                        return [
                            Forms\Components\Placeholder::make('invoice_check')
                                ->label('Billing & Invoice Clearance')
                                ->content(function () use ($record) {
                                    $invoices = $record->invoices;
                                    if ($invoices->isEmpty()) {
                                        return new HtmlString('<div class="p-2.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-xs text-amber-600 font-semibold">⚠️ Notice: No freight invoice has been generated for this consignment yet. Confirm customer settlement before release.</div>');
                                    }
                                    $unpaid = $invoices->filter(fn ($inv) => $inv->balance > 0)->count();
                                    if ($unpaid > 0) {
                                        return new HtmlString('<div class="p-2.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-xs text-rose-600 font-bold">⚠️ Warning: Customer has an unpaid invoice balance on this shipment! Ensure clearance or manager approval before physical release.</div>');
                                    }
                                    return new HtmlString('<div class="p-2.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-xs text-emerald-600 font-bold">✓ Clearance Verified: Invoices are fully paid. Authorized for immediate checkout.</div>');
                                }),

                            Forms\Components\TextInput::make('receiver_name')
                                ->label('Authorized Collector Name')
                                ->default(fn () => $record->receiver_name ?? $record->customer?->name)
                                ->required(),
                            Forms\Components\TextInput::make('receiver_phone')
                                ->label('Collector Phone Number')
                                ->default(fn () => $record->receiver_phone ?? $record->customer?->phone_number)
                                ->tel()
                                ->required(),
                            Forms\Components\TextInput::make('receiver_id_number')
                                ->label('National ID / Passport Number')
                                ->placeholder('e.g. CM89012345ABC')
                                ->required(),
                            Forms\Components\TextInput::make('receiver_vehicle_no')
                                ->label('Transport Vehicle Plate')
                                ->placeholder('e.g. UBD 450T')
                                ->required(),
                            Forms\Components\Textarea::make('checkout_notes')
                                ->label('Handover Condition & Remarks')
                                ->default('Packages verified sound, complete count, and released in good condition.')
                                ->required(),
                        ];
                    })
                    ->action(function (Shippment $record, array $data): void {
                        $gatePassNo = $record->gate_pass_number ?? Shippment::generateGatePassNumber();
                        $record->update([
                            'cargo_status' => 'Delivered',
                            'dispatched_at' => now(),
                            'receiver_name' => $data['receiver_name'],
                            'receiver_phone' => $data['receiver_phone'],
                            'receiver_id_number' => $data['receiver_id_number'],
                            'receiver_vehicle_no' => $data['receiver_vehicle_no'],
                            'gate_pass_number' => $gatePassNo,
                            'checked_out_by' => Auth::id(),
                            'checkout_notes' => $data['checkout_notes'],
                        ]);

                        Notification::make()
                            ->title('Cargo Released & Gate Pass Issued')
                            ->body("Gate Pass {$gatePassNo} generated. Goods checked out from warehouse.")
                            ->success()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('print')
                                    ->label('Print Gate Pass')
                                    ->url(route('warehouse.gate-pass.print', $record->id))
                                    ->openUrlInNewTab(),
                            ])
                            ->send();
                    }),

                Tables\Actions\Action::make('generateInvoice')
                    ->label('Generate Invoice')
                    ->icon('heroicon-o-document-currency-dollar')
                    ->color('info')
                    ->visible(fn (Shippment $record) => $record->invoices->isEmpty())
                    ->modalHeading(fn (Shippment $record) => "Auto-Generate Freight Invoice for {$record->mark}")
                    ->modalDescription(fn (Shippment $record) => "Consignee: " . ($record->customer->name ?? 'Customer') . ". Enter freight and clearance charges below to create the invoice instantly.")
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
                            ->label('Other Port / Handling Charges ($)')
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
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('print')
                                        ->label('Print Invoice')
                                        ->button()
                                        ->url(route('invoices.print', $invoice->id))
                                        ->openUrlInNewTab(),
                                ])
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('printGatePass')
                    ->label('Gate Pass')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (Shippment $record) => !empty($record->gate_pass_number) || $record->cargo_status === 'Delivered')
                    ->url(fn (Shippment $record) => route('warehouse.gate-pass.print', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->striped()
            ->emptyStateHeading('No Cargo Consignments Recorded')
            ->emptyStateDescription('Record incoming sea or air freight cargo to track warehouse inventory and releases.')
            ->emptyStateIcon('heroicon-o-truck')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippments::route('/'),
            'create' => Pages\CreateShippment::route('/create'),
            'edit' => Pages\EditShippment::route('/{record}/edit'),
        ];
    }
}
