<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\Shippment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ShippmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'shippments';

    protected static ?string $recordTitleAttribute = 'mark';

    protected static ?string $title = 'Customer Cargo & Consignments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transit_id')
                    ->relationship('transit', 'number')
                    ->label('Container / Transit')
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
                    ->label('Description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transit.number')
                    ->label('Container')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mark')
                    ->label('Mark')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_of_packages')
                    ->label('Pkgs'),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight')
                    ->suffix(' KG'),
                Tables\Columns\TextColumn::make('cbm')
                    ->label('CBM'),
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
                Tables\Columns\TextColumn::make('warehouse_location')
                    ->label('Shelf')
                    ->placeholder('Unassigned'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Consignment')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Tables\Actions\Action::make('printGatePass')
                    ->label('Gate Pass')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (Shippment $record) => !empty($record->gate_pass_number) || $record->cargo_status === 'Delivered')
                    ->url(fn (Shippment $record) => route('warehouse.gate-pass.print', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }
}
