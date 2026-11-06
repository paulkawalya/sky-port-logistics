<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippmentResource\Pages;
use App\Filament\Resources\ShippmentResource\RelationManagers;
use App\Models\Shippment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                            ->required(),
                        Forms\Components\Select::make('agent_id')
                            ->relationship('agent', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Agent')
                            ->required(),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mark')
                    ->label('Mark')
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
                    ->searchable(),
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
                    ->label('Release / Deliver')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn ($record) => $record->cargo_status === 'Ready for Pickup')
                    ->form([
                        Forms\Components\TextInput::make('receiver_name')
                            ->label('Picked up by (Name)')
                            ->required(),
                        Forms\Components\TextInput::make('receiver_phone')
                            ->label('Phone Number')
                            ->required(),
                    ])
                    ->action(fn ($record, array $data) => $record->update([
                        'cargo_status' => 'Delivered',
                        'dispatched_at' => now(),
                        'receiver_name' => $data['receiver_name'],
                        'receiver_phone' => $data['receiver_phone'],
                    ])),
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
