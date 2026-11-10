<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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

                Forms\Components\Section::make('Invoice Line Items & Particulars')
                    ->description('Itemize ocean/air freight rates, BL fees, port duties, or clearance expenses.')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\TextInput::make('particular')
                                    ->label('Particular / Item Description')
                                    ->required()
                                    ->placeholder('e.g. Ocean Freight / Handling / Bill of Lading')
                                    ->columnSpan(['default' => 12, 'md' => 6]),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(['default' => 6, 'md' => 2]),
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Rate / Unit Price')
                                    ->numeric()
                                    ->prefix(fn (callable $get) => $get('../../transaction_currency') === 'UGX' ? 'UGX' : '$')
                                    ->required()
                                    ->columnSpan(['default' => 6, 'md' => 4]),
                            ])
                            ->columns(['default' => 1, 'sm' => 2, 'md' => 12])
                            ->defaultItems(1)
                            ->addActionLabel('Add Invoice Particular'),
                    ]),
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
                Tables\Actions\Action::make('recordPayment')
                    ->label('Record Payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->slideOver()
                    ->visible(fn (Invoice $record) => $record->balance > 0)
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('amount_paid')
                            ->label('Amount Received')
                            ->numeric()
                            ->required()
                            ->default(fn (Invoice $record) => $record->balance)
                            ->maxValue(fn (Invoice $record) => $record->balance)
                            ->prefix(fn (Invoice $record) => $record->transaction_currency ?? 'USD'),
                        Forms\Components\Select::make('mode_of_payment')
                            ->label('Mode of Payment')
                            ->options([
                                'Cash' => 'Cash',
                                'Bank' => 'Bank Transfer',
                                'Mobile Money' => 'Mobile Money',
                                'Cheque' => 'Cheque',
                                'Digital' => 'Digital / Card',
                            ])
                            ->default('Cash')
                            ->required(),
                        Forms\Components\Select::make('payment_account_id')
                            ->label('Destination Account (Cash/Bank)')
                            ->options(PaymentAccount::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Textarea::make('narrative')
                            ->label('Notes / Remarks')
                            ->placeholder('e.g. Full settlement for cargo release'),
                    ])
                    ->action(function (Invoice $record, array $data): void {
                        $payment = new InvoicePayment();
                        $payment->invoice_id = $record->id;
                        $payment->user_id = Auth::id() ?? 1;
                        $payment->payment_date = $data['payment_date'];
                        $payment->amount_paid = $data['amount_paid'];
                        $payment->mode_of_payment = $data['mode_of_payment'];
                        $payment->narrative = $data['narrative'] ?? 'Freight Invoice Payment';
                        $payment->save();

                        if (!empty($data['payment_account_id'])) {
                            $particular = 'Payment for Freight Invoice INV-' . str_pad($record->id, 4, '0', STR_PAD_LEFT) . ' - ' . ($record->shippment?->customer?->name ?? 'Customer');
                            Transaction::saveTransaction(
                                $data['payment_account_id'],
                                $payment->amount_paid,
                                $payment->payment_date,
                                Auth::id() ?? 1,
                                null,
                                'credit',
                                $particular
                            );
                        }

                        $record->clearCache();

                        Notification::make()
                            ->title('Payment Recorded')
                            ->body("Recorded {$record->transaction_currency} " . number_format($data['amount_paid'], 2) . " for Invoice INV-" . str_pad($record->id, 4, '0', STR_PAD_LEFT))
                            ->success()
                            ->send();
                    }),
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
            RelationManagers\PaymentsRelationManager::class,
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
