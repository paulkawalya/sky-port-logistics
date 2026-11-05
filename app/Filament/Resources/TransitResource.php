<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransitResource\Pages;
use App\Filament\Resources\TransitResource\RelationManagers;
use App\Models\Transit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransitResource extends Resource
{
    protected static ?string $model = Transit::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationGroup = 'Operations & Freight';

    protected static ?string $navigationLabel = 'Containers & Transits';

    protected static ?string $modelLabel = 'Transit / Container';

    protected static ?string $pluralModelLabel = 'Containers & Transits';

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
                    ])->columns(2),

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
            'index' => Pages\ListTransits::route('/'),
            'create' => Pages\CreateTransit::route('/create'),
            'edit' => Pages\EditTransit::route('/{record}/edit'),
        ];
    }
}
