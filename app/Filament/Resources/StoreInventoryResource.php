<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreInventoryResource\Pages;
use App\Models\Shippment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class StoreInventoryResource extends Resource
{
    protected static ?string $model = Shippment::class;

    protected static ?string $slug = 'store-inventory';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Operations & Freight';

    protected static ?string $navigationLabel = 'Store Inventory & Checkout';

    protected static ?string $modelLabel = 'Store Inventory Item';

    protected static ?string $pluralModelLabel = 'Store Inventory & Checkout';

    protected static ?string $recordTitleAttribute = 'mark';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['customer', 'transit', 'checkedOutBy', 'invoices']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Store Inventory & Warehouse Tracking')
                    ->description('Record warehouse intake location, package count, and storage status.')
                    ->schema([
                        Forms\Components\Select::make('transit_id')
                            ->relationship('transit', 'number')
                            ->searchable()
                            ->preload()
                            ->label('Container / Transit #')
                            ->required(),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Customer / Consignee')
                            ->required(),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Clearing / Origin Agent')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('mark')
                            ->label('Shipping Mark')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('number_of_packages')
                            ->label('Packages Count')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('unit_of_measure')
                            ->label('Unit of Measure')
                            ->default('PCS'),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (KG)')
                            ->numeric(),
                        Forms\Components\TextInput::make('cbm')
                            ->label('Volume (CBM)')
                            ->numeric(),
                        Forms\Components\Select::make('cargo_status')
                            ->label('Store / Warehouse Status')
                            ->options([
                                'In Transit' => 'In Transit (Expected)',
                                'Received in Store' => 'Received in Store',
                                'Sorted & Inspected' => 'Sorted & Inspected',
                                'Ready for Pickup' => 'Ready for Pickup',
                                'Delivered' => 'Delivered / Checked Out',
                            ])
                            ->default('Received in Store')
                            ->required(),
                        Forms\Components\TextInput::make('warehouse_location')
                            ->label('Warehouse Bay / Shelf / Rack Location')
                            ->placeholder('e.g. Bay 2 - Rack B4 (Kampala Terminal)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('received_in_store_at')
                            ->label('Intake Date & Time')
                            ->default(now()),
                    ])->columns(2),

                Forms\Components\Section::make('Checkout & Gate Pass Release')
                    ->description('Details of the authorized person and vehicle collecting cargo from the warehouse.')
                    ->schema([
                        Forms\Components\DateTimePicker::make('dispatched_at')
                            ->label('Checkout / Release Timestamp'),
                        Forms\Components\TextInput::make('gate_pass_number')
                            ->label('Gate Pass Reference')
                            ->disabled()
                            ->placeholder('Auto-generated at checkout'),
                        Forms\Components\TextInput::make('receiver_name')
                            ->label('Collector Full Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('receiver_phone')
                            ->label('Collector Phone Number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('receiver_id_number')
                            ->label('National ID / Passport / Permit No.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('receiver_vehicle_no')
                            ->label('Truck / Van Registration Plate')
                            ->placeholder('e.g. UBD 123X')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('checkout_notes')
                            ->label('Condition of Goods on Release')
                            ->placeholder('e.g. All packages verified sound and intact.')
                            ->columnSpanFull(),
                    ])->columns(2)->collapsible(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Store Cargo Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('mark')
                            ->label('Shipping Mark')
                            ->weight('bold')
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label('Customer / Consignee')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('customer.phone_number')
                            ->label('Customer Phone'),
                        Infolists\Components\TextEntry::make('transit.number')
                            ->label('Container / Flight #')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('cargo_status')
                            ->label('Current Store Status')
                            ->badge()
                            ->colors([
                                'gray' => 'In Transit',
                                'warning' => 'Received in Store',
                                'info' => 'Sorted & Inspected',
                                'primary' => 'Ready for Pickup',
                                'success' => 'Delivered',
                            ]),
                        Infolists\Components\TextEntry::make('warehouse_location')
                            ->label('Warehouse Bay / Shelf')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Unassigned'),
                        Infolists\Components\TextEntry::make('number_of_packages')
                            ->label('Packages Count')
                            ->formatStateUsing(fn ($state, Shippment $record) => "{$state} " . ($record->unit_of_measure ?? 'PCS')),
                        Infolists\Components\TextEntry::make('weight')
                            ->label('Weight')
                            ->suffix(' KG'),
                        Infolists\Components\TextEntry::make('cbm')
                            ->label('CBM Volume')
                            ->suffix(' m³'),
                        Infolists\Components\TextEntry::make('received_in_store_at')
                            ->label('Intake Date')
                            ->dateTime()
                            ->placeholder('Pre-arrival'),
                        Infolists\Components\TextEntry::make('days_in_store')
                            ->label('Days in Warehouse')
                            ->formatStateUsing(fn ($state) => $state !== null ? "{$state} Day(s)" : 'N/A')
                            ->badge()
                            ->color(fn ($state) => match (true) {
                                $state === null => 'gray',
                                $state > 14 => 'danger',
                                $state > 7 => 'warning',
                                default => 'success',
                            }),
                    ])->columns(3),

                Infolists\Components\Section::make('Checkout & Gate Pass Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('gate_pass_number')
                            ->label('Gate Pass #')
                            ->badge()
                            ->color('success')
                            ->placeholder('Not checked out yet'),
                        Infolists\Components\TextEntry::make('dispatched_at')
                            ->label('Checkout Timestamp')
                            ->dateTime()
                            ->placeholder('Pending collection'),
                        Infolists\Components\TextEntry::make('receiver_name')
                            ->label('Released To Person')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('receiver_phone')
                            ->label('Receiver Phone')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('receiver_id_number')
                            ->label('Receiver National ID / Passport')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('receiver_vehicle_no')
                            ->label('Transport Vehicle Plate')
                            ->badge()
                            ->color('info')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('checkedOutBy.name')
                            ->label('Storekeeper')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('checkout_notes')
                            ->label('Release Notes / Condition')
                            ->columnSpanFull()
                            ->placeholder('None recorded.'),
                    ])->columns(3)->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mark')
                    ->label('Shipping Mark')
                    ->weight('bold')
                    ->description(fn (Shippment $record) => "{$record->number_of_packages} " . ($record->unit_of_measure ?? 'PCS') . " | {$record->weight} KG")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->description(fn (Shippment $record) => $record->customer?->phone_number)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transit.number')
                    ->label('Container/AWB')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warehouse_location')
                    ->label('Bay / Shelf')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->placeholder('Unassigned'),
                Tables\Columns\TextColumn::make('received_in_store_at')
                    ->label('Intake Date')
                    ->date()
                    ->sortable()
                    ->placeholder('Pending arrival'),
                Tables\Columns\TextColumn::make('days_in_store')
                    ->label('Store Aging')
                    ->formatStateUsing(fn ($state) => $state !== null ? "{$state}d" : 'N/A')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state === null => 'gray',
                        $state > 14 => 'danger',
                        $state > 7 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => 'In Transit',
                        'warning' => 'Received in Store',
                        'info' => 'Sorted & Inspected',
                        'primary' => 'Ready for Pickup',
                        'success' => 'Delivered',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('gate_pass_number')
                    ->label('Gate Pass #')
                    ->badge()
                    ->color('success')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('receiver_vehicle_no')
                    ->label('Vehicle')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('cargo_status')
                    ->options([
                        'In Transit' => 'In Transit',
                        'Received in Store' => 'Received in Store',
                        'Sorted & Inspected' => 'Sorted & Inspected',
                        'Ready for Pickup' => 'Ready for Pickup',
                        'Delivered' => 'Delivered / Checked Out',
                    ]),
                Tables\Filters\SelectFilter::make('transit_id')
                    ->relationship('transit', 'number')
                    ->label('Container'),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Customer')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),

                Tables\Actions\Action::make('receiveInStore')
                    ->label('Intake')
                    ->icon('heroicon-o-arrow-down-on-square')
                    ->color('warning')
                    ->visible(fn (Shippment $record) => $record->cargo_status === 'In Transit')
                    ->form([
                        Forms\Components\TextInput::make('warehouse_location')
                            ->label('Warehouse Bay / Shelf / Rack Location')
                            ->required()
                            ->placeholder('e.g. Bay 2 - Shelf B4'),
                        Forms\Components\DateTimePicker::make('received_in_store_at')
                            ->label('Intake Time')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (Shippment $record, array $data): void {
                        $record->update([
                            'cargo_status' => 'Received in Store',
                            'warehouse_location' => $data['warehouse_location'],
                            'received_in_store_at' => $data['received_in_store_at'],
                        ]);

                        Notification::make()
                            ->title('Cargo Received in Store')
                            ->body("Mark {$record->mark} placed in {$data['warehouse_location']}.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('markReady')
                    ->label('Ready')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->visible(fn (Shippment $record) => in_array($record->cargo_status, ['Received in Store', 'Sorted & Inspected']))
                    ->action(function (Shippment $record): void {
                        $record->update([
                            'cargo_status' => 'Ready for Pickup',
                        ]);

                        Notification::make()
                            ->title('Cargo Ready for Pickup')
                            ->body("Consignment {$record->mark} marked ready for client collection.")
                            ->info()
                            ->send();
                    }),

                Tables\Actions\Action::make('checkout')
                    ->label('Checkout & Release')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->slideOver()
                    ->visible(fn (Shippment $record) => in_array($record->cargo_status, ['Received in Store', 'Sorted & Inspected', 'Ready for Pickup']))
                    ->form(function (Shippment $record) {
                        return [
                            Forms\Components\Placeholder::make('invoice_check')
                                ->label('Consignment Billing & Invoice Clearance')
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
                                ->label('National ID / Passport / Permit Number')
                                ->placeholder('e.g. CM89012345ABC')
                                ->required(),
                            Forms\Components\TextInput::make('receiver_vehicle_no')
                                ->label('Transport Vehicle Plate Number')
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

                Tables\Actions\Action::make('printGatePass')
                    ->label('Gate Pass')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (Shippment $record) => !empty($record->gate_pass_number) || $record->cargo_status === 'Delivered')
                    ->url(fn (Shippment $record) => route('warehouse.gate-pass.print', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->emptyStateHeading('No Inventory in Warehouse')
            ->emptyStateDescription('Intake incoming cargo to begin store inventory tracking, shelf location assignments, and checkout verification.')
            ->emptyStateIcon('heroicon-o-building-storefront');
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
            'index' => Pages\ListStoreInventories::route('/'),
            'create' => Pages\CreateStoreInventory::route('/create'),
            'edit' => Pages\EditStoreInventory::route('/{record}/edit'),
        ];
    }
}
