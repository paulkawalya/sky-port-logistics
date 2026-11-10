<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManualInvoiceResource\Pages;
use App\Models\PaymentAccount;
use App\Models\Quotation;
use App\Models\QuotationPayment;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ManualInvoiceResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $slug = 'manual-invoices';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?string $navigationLabel = 'Manual Invoices';

    protected static ?string $modelLabel = 'Manual Invoice';

    protected static ?string $pluralModelLabel = 'Manual Invoices';

    protected static ?string $recordTitleAttribute = 'container_air_no';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('transaction_type', 'invoice')
            ->with(['customer', 'details', 'payments']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Header')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone_number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\DatePicker::make('date_quoted')
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
                        Forms\Components\Hidden::make('transaction_type')
                            ->default('invoice'),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => Auth::id()),
                    ])->columns(3),

                Forms\Components\Section::make('Cargo & Reference Specifications')
                    ->schema([
                        Forms\Components\TextInput::make('container_air_no')
                            ->label('Container / Air Waybill No.')
                            ->placeholder('e.g. MSKU1234567 or AWB-987654')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('entry_no')
                            ->label('Customs Entry No.')
                            ->placeholder('e.g. C-89211')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('packages')
                            ->label('Total Packages')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight (KG)')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('volume')
                            ->label('Volume (CBM)')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\Textarea::make('description')
                            ->label('Notes / Description / Consignment Particulars')
                            ->placeholder('Enter additional billing or cargo details...')
                            ->columnSpanFull(),
                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('Invoice Line Items')
                    ->description('Add goods, handling fees, shipping charges, or direct services.')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\TextInput::make('particular')
                                    ->label('Description / Item')
                                    ->required()
                                    ->placeholder('e.g. Ocean freight charge / Warehouse demurrage')
                                    ->columnSpan(['default' => 12, 'md' => 6]),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(['default' => 6, 'md' => 2]),
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->prefix(fn (callable $get) => $get('../../transaction_currency') === 'UGX' ? 'UGX' : '$')
                                    ->required()
                                    ->columnSpan(['default' => 6, 'md' => 4]),
                            ])
                            ->columns(['default' => 1, 'sm' => 2, 'md' => 12])
                            ->defaultItems(1)
                            ->addActionLabel('Add Invoice Line Item'),
                    ]),
            ]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Section::make('Invoice Header & Balances')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('id')
                            ->label('Invoice Reference')
                            ->formatStateUsing(fn ($state) => 'INV-M-' . str_pad($state, 4, '0', STR_PAD_LEFT))
                            ->weight('bold')
                            ->badge()
                            ->color('info'),
                        \Filament\Infolists\Components\TextEntry::make('date_quoted')
                            ->label('Invoice Date')
                            ->date(),
                        \Filament\Infolists\Components\TextEntry::make('customer.name')
                            ->label('Customer Name')
                            ->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('customer.phone_number')
                            ->label('Contact Phone'),
                        \Filament\Infolists\Components\TextEntry::make('transaction_currency')
                            ->label('Billing Currency')
                            ->badge(),
                        \Filament\Infolists\Components\TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Paid' => 'success',
                                'Partial' => 'warning',
                                'Unpaid' => 'danger',
                                default => 'gray',
                            }),
                        \Filament\Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Billed')
                            ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                            ->weight('bold'),
                        \Filament\Infolists\Components\TextEntry::make('total_paid')
                            ->label('Amount Paid')
                            ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                            ->color('success'),
                        \Filament\Infolists\Components\TextEntry::make('balance')
                            ->label('Outstanding Balance Due')
                            ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                            ->weight('bold'),
                    ])->columns(3),

                \Filament\Infolists\Components\Section::make('Cargo Specifications')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('container_air_no')
                            ->label('Container / AWB #')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('entry_no')
                            ->label('Customs Entry #')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('packages')
                            ->label('Packages')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('weight')
                            ->label('Weight (KG)')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('volume')
                            ->label('Volume (CBM)')
                            ->placeholder('N/A'),
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Notes')
                            ->columnSpanFull()
                            ->placeholder('None'),
                    ])->columns(3)->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Invoice #')
                    ->formatStateUsing(fn ($state) => 'INV-M-' . str_pad($state, 4, '0', STR_PAD_LEFT))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_quoted')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->description(fn (Quotation $record) => $record->customer?->phone_number)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('container_air_no')
                    ->label('Ref / Container')
                    ->placeholder('N/A')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->label('Currency')
                    ->badge()
                    ->color(fn ($state) => $state === 'USD' ? 'success' : 'primary'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Billed Amount')
                    ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Paid')
                    ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                    ->color('success')
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance Due')
                    ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Partial' => 'warning',
                        'Unpaid' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_currency')
                    ->options([
                        'USD' => 'USD ($)',
                        'UGX' => 'UGX (Shs)',
                    ]),
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver(),
                Tables\Actions\Action::make('recordPayment')
                    ->label('Record Payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->slideOver()
                    ->visible(fn (Quotation $record) => $record->balance > 0)
                    ->form([
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Payment Date')
                            ->default(now())
                            ->required(),
                        Forms\Components\TextInput::make('amount_paid')
                            ->label('Amount Paid')
                            ->numeric()
                            ->required()
                            ->default(fn (Quotation $record) => $record->balance)
                            ->maxValue(fn (Quotation $record) => $record->balance)
                            ->prefix(fn (Quotation $record) => $record->transaction_currency),
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
                            ->label('Payment Account (Destination)')
                            ->options(PaymentAccount::all()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Select payment account (optional)'),
                        Forms\Components\Textarea::make('narrative')
                            ->label('Notes / Particulars')
                            ->placeholder('e.g. Partial settlement for customs & freight'),
                    ])
                    ->action(function (Quotation $record, array $data): void {
                        $payment = QuotationPayment::create([
                            'quotation_id' => $record->id,
                            'user_id' => Auth::id(),
                            'payment_date' => $data['payment_date'],
                            'amount_paid' => $data['amount_paid'],
                            'mode_of_payment' => $data['mode_of_payment'],
                            'narrative' => $data['narrative'] ?? 'Manual Invoice Payment',
                            'payment_account_id' => $data['payment_account_id'] ?? null,
                        ]);

                        if (!empty($data['payment_account_id'])) {
                            $particular = 'Payment for Manual Invoice INV-M-' . str_pad($record->id, 4, '0', STR_PAD_LEFT) . ' - ' . ($record->customer?->name ?? 'Customer');
                            Transaction::saveTransaction(
                                $data['payment_account_id'],
                                $payment->amount_paid,
                                $payment->payment_date,
                                Auth::id(),
                                null,
                                'credit',
                                $particular
                            );
                        }

                        Notification::make()
                            ->title('Payment Recorded')
                            ->body("Successfully received {$record->transaction_currency} " . number_format($data['amount_paid']) . " for Invoice INV-M-" . str_pad($record->id, 4, '0', STR_PAD_LEFT))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Quotation $record) => route('quotations.print', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Quotation $record) => route('quotations.pdf', $record->id)),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->emptyStateHeading('No Manual Invoices Created')
            ->emptyStateDescription('Create direct customer invoices for handling fees, demurrage, local transport, and ad-hoc cargo.')
            ->emptyStateIcon('heroicon-o-document-text');
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
            'index' => Pages\ListManualInvoices::route('/'),
            'create' => Pages\CreateManualInvoice::route('/create'),
            'edit' => Pages\EditManualInvoice::route('/{record}/edit'),
        ];
    }
}

