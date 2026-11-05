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
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mark')
                    ->label('Mark')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number_of_packages')
                    ->label('Packages')
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
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transit_id')
                    ->relationship('transit', 'number')
                    ->label('Filter by Container'),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Filter by Customer'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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
