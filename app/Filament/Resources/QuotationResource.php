<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Filament\Resources\QuotationResource\RelationManagers;
use App\Models\Quotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quotation Details')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('date_quoted')
                            ->label('Quotation Date')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('transaction_currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'UGX' => 'UGX (Shs)',
                            ])
                            ->default('USD')
                            ->required(),
                        Forms\Components\Select::make('transaction_type')
                            ->options([
                                'Sea Freight' => 'Sea Freight',
                                'Air Freight' => 'Air Freight',
                                'Clearance Only' => 'Clearance Only',
                                'Door Delivery' => 'Door Delivery',
                            ])
                            ->default('Sea Freight')
                            ->required(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                    ])->columns(2),

                Forms\Components\Section::make('Cargo & Transport Specifications')
                    ->schema([
                        Forms\Components\TextInput::make('container_air_no')
                            ->label('Container / Air Waybill No.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('entry_no')
                            ->label('Customs Entry No.')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('packages')
                            ->label('Packages')
                            ->numeric(),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (KG)')
                            ->numeric(),
                        Forms\Components\TextInput::make('volume')
                            ->label('Volume (CBM)')
                            ->numeric(),
                        Forms\Components\Textarea::make('description')
                            ->label('Quotation Description / Notes')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#Quote')
                    ->prefix('QTN-')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('container_air_no')
                    ->label('Container/AWB')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_quoted')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('packages')
                    ->label('Pkgs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight (KG)')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type'),
                Tables\Filters\SelectFilter::make('transaction_currency'),
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}
