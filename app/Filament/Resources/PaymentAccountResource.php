<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentAccountResource\Pages;
use App\Filament\Resources\PaymentAccountResource\RelationManagers;
use App\Models\PaymentAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentAccountResource extends Resource
{
    protected static ?string $model = PaymentAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Account Name / Bank')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->label('Account / Phone Number')
                    ->maxLength(255),
                Forms\Components\Select::make('account_type')
                    ->options([
                        'Bank' => 'Bank Account',
                        'Cash' => 'Cash / Safe',
                        'Mobile Money' => 'Mobile Money',
                    ])
                    ->default('Bank')
                    ->required(),
                Forms\Components\Select::make('transaction_currency')
                    ->options([
                        'UGX' => 'UGX (Shs)',
                        'USD' => 'USD ($)',
                        'EUR' => 'EUR (€)',
                        'KES' => 'KES (KSh)',
                    ])
                    ->default('UGX')
                    ->required(),
                Forms\Components\TextInput::make('others')
                    ->label('Notes / Branch')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Account Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->label('Account / Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Available Balance')
                    ->money(fn ($record) => $record->transaction_currency ?? 'UGX')
                    ->weight('bold')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('account_type')
                    ->options([
                        'Bank' => 'Bank',
                        'Cash' => 'Cash',
                        'Mobile Money' => 'Mobile Money',
                    ]),
                Tables\Filters\SelectFilter::make('transaction_currency')
                    ->options([
                        'UGX' => 'UGX',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'KES' => 'KES',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\EditAction::make(),
            ])
            ->striped()
            ->emptyStateHeading('No Payment Accounts Configured')
            ->emptyStateDescription('Set up bank accounts, cash safe, and mobile money ledgers.')
            ->emptyStateIcon('heroicon-o-credit-card')
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
            'index' => Pages\ListPaymentAccounts::route('/'),
            'create' => Pages\CreatePaymentAccount::route('/create'),
            'edit' => Pages\EditPaymentAccount::route('/{record}/edit'),
        ];
    }
}
