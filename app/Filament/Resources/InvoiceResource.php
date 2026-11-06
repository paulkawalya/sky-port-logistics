<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Information')
                    ->schema([
                        Forms\Components\Select::make('shippment_id')
                            ->relationship('shippment', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "Shipment #{$record->id} - {$record->mark} ({$record->customer?->name})")
                            ->label('Shipment')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('date_invoiced')
                            ->label('Invoice Date')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('transaction_currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'UGX' => 'UGX (Shs)',
                            ])
                            ->default('USD')
                            ->required(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Invoice #')
                    ->prefix('INV-')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('shippment.customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shippment.mark')
                    ->label('Shipping Mark')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_invoiced')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'USD' ? 'success' : 'info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Billed')
                    ->money(fn ($record) => $record->transaction_currency ?? 'USD')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')
                    ->label('Paid')
                    ->money(fn ($record) => $record->transaction_currency ?? 'USD')
                    ->color('success'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance Due')
                    ->money(fn ($record) => $record->transaction_currency ?? 'USD')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'partially_paid',
                        'success' => 'paid',
                    ]),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_currency')
                    ->options([
                        'USD' => 'USD',
                        'UGX' => 'UGX',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('invoices.print', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn ($record) => route('invoices.pdf', $record->id)),
                Tables\Actions\EditAction::make(),
            ])
            ->striped()
            ->emptyStateHeading('No Freight Invoices Generated')
            ->emptyStateDescription('Generate an invoice from an existing cargo consignment to bill freight, storage, and clearance charges.')
            ->emptyStateIcon('heroicon-o-document-currency-dollar')
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
