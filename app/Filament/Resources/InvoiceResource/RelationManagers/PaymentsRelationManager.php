<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use App\Models\InvoicePayment;
use App\Models\PaymentAccount;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $recordTitleAttribute = 'amount_paid';

    protected static ?string $title = 'Payment Receipts & Ledger Entries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('payment_date')
                    ->label('Payment Date')
                    ->default(now())
                    ->required(),
                Forms\Components\TextInput::make('amount_paid')
                    ->label('Amount Paid')
                    ->numeric()
                    ->required(),
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
                    ->label('Notes / Particulars')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Amount Received')
                    ->money(fn () => $this->getOwnerRecord()->transaction_currency ?? 'USD')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('mode_of_payment')
                    ->label('Method')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('narrative')
                    ->label('Remarks')
                    ->placeholder('None'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Received By')
                    ->placeholder('System'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Record Payment')
                    ->icon('heroicon-o-plus-circle')
                    ->slideOver()
                    ->using(function (array $data): InvoicePayment {
                        $invoice = $this->getOwnerRecord();
                        $payment = new InvoicePayment();
                        $payment->invoice_id = $invoice->id;
                        $payment->user_id = Auth::id() ?? 1;
                        $payment->payment_date = $data['payment_date'];
                        $payment->amount_paid = $data['amount_paid'];
                        $payment->mode_of_payment = $data['mode_of_payment'];
                        $payment->narrative = $data['narrative'] ?? 'Freight Invoice Payment';
                        $payment->save();

                        if (!empty($data['payment_account_id'])) {
                            $particular = 'Payment for Freight Invoice INV-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT) . ' - ' . ($invoice->shippment?->customer?->name ?? 'Customer');
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

                        $invoice->clearCache();

                        Notification::make()
                            ->title('Payment Recorded')
                            ->body("Successfully received " . number_format($data['amount_paid'], 2))
                            ->success()
                            ->send();

                        return $payment;
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->after(fn () => $this->getOwnerRecord()->clearCache()),
            ]);
    }
}
