<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('transaction_date')
                    ->default(now())
                    ->required(),
                Forms\Components\Select::make('payment_account_id')
                    ->relationship('paymentAccount', 'name')
                    ->label('Target Account')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('source_payment_account_id')
                    ->relationship('sourcePaymentAccount', 'name')
                    ->label('Source Account (for Transfers)')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('transaction_type')
                    ->options([
                        'credit' => 'Credit (Money In / Inflow)',
                        'debit' => 'Debit (Money Out / Outflow)',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('transaction_currency')
                    ->options([
                        'USD' => 'USD ($)',
                        'UGX' => 'UGX (Shs)',
                    ])
                    ->default('USD')
                    ->required(),
                Forms\Components\TextInput::make('particular')
                    ->label('Particulars / Reference')
                    ->maxLength(255),
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentAccount.name')
                    ->label('Account')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('particular')
                    ->label('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'credit' => 'success',
                        'debit' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type'),
                Tables\Filters\SelectFilter::make('payment_account_id')
                    ->relationship('paymentAccount', 'name')
                    ->label('Account'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\EditAction::make(),
            ])
            ->striped()
            ->emptyStateHeading('No Transactions Logged')
            ->emptyStateDescription('Ledger entries will appear here as payments and disbursements are posted.')
            ->emptyStateIcon('heroicon-o-arrows-right-left')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
