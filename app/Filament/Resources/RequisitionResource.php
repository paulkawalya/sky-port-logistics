<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequisitionResource\Pages;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentAccount;
use App\Models\Requisition;
use App\Models\RequisitionApprover;
use App\Models\RequisitionPayment;
use App\Models\Supplier;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RequisitionResource extends Resource
{
    protected static ?string $model = Requisition::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?string $navigationLabel = 'Requisitions';

    protected static ?string $modelLabel = 'Requisition';

    protected static ?string $pluralModelLabel = 'Requisitions';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'details.category', 'approvals.user', 'documents']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Requisition Header')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Requested By')
                            ->default(fn () => Auth::id())
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('date_requisitioned')
                            ->label('Requisition Date')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'UGX' => 'UGX - Uganda Shillings',
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'KES' => 'KES - Kenya Shillings',
                            ])
                            ->default('UGX')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                        Forms\Components\Textarea::make('remarks')
                            ->label('General Purpose / Justification')
                            ->placeholder('State purpose of requisition and business justification...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Requisition Line Items (Budget Breakdown)')
                    ->description('Specify each expenditure item, quantity, unit cost, and category.')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\TextInput::make('particular')
                                    ->label('Item / Expense Particular')
                                    ->required()
                                    ->placeholder('e.g. Clearance handling fee / Office supplies')
                                    ->columnSpan(4),
                                Forms\Components\Select::make('expense_category_id')
                                    ->relationship('category', 'name')
                                    ->label('Category')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Category Name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('particular')
                                            ->label('Particular / Description')
                                            ->maxLength(255),
                                    ])
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty')
                                    ->default(1)
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('estimated_cost')
                                    ->label('Unit Cost')
                                    ->numeric()
                                    ->required()
                                    ->prefix(fn (callable $get) => $get('../../currency') === 'USD' ? '$' : 'Shs')
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('reason')
                                    ->label('Line Item Justification / Notes')
                                    ->placeholder('Optional notes on this specific expenditure')
                                    ->columnSpanFull(),
                            ])
                            ->columns(12)
                            ->defaultItems(1)
                            ->addActionLabel('Add Expense Line Item')
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Supporting Documents & Attachments')
                    ->description('Attach supplier proforma invoices, quotes, receipts, or authorizations.')
                    ->schema([
                        Forms\Components\Repeater::make('documents')
                            ->relationship('documents')
                            ->schema([
                                Forms\Components\TextInput::make('file_name')
                                    ->label('Document Title / Description')
                                    ->placeholder('e.g. Supplier Proforma / Quotation')
                                    ->required()
                                    ->columnSpan(6),
                                Forms\Components\FileUpload::make('file_url')
                                    ->label('File Attachment')
                                    ->disk('public')
                                    ->directory('documents')
                                    ->visibility('public')
                                    ->required()
                                    ->columnSpan(6),
                                Forms\Components\Hidden::make('user_id')
                                    ->default(fn () => Auth::id()),
                            ])
                            ->columns(12)
                            ->addActionLabel('Attach Supporting Document')
                            ->collapsible(),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('REQ #')
                    ->formatStateUsing(fn ($state) => 'REQ-' . str_pad($state, 4, '0', STR_PAD_LEFT))
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_requisitioned')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Requested By')
                    ->description(fn (Requisition $record) => $record->user?->email)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Cur')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'USD' => 'success',
                        'EUR' => 'warning',
                        'KES' => 'info',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Budget')
                    ->money(fn (Requisition $record) => $record->currency ?? 'UGX')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Disbursed')
                    ->money(fn (Requisition $record) => $record->currency ?? 'UGX')
                    ->color('success')
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->money(fn (Requisition $record) => $record->currency ?? 'UGX')
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'approved' => 'heroicon-m-check-badge',
                        'rejected' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-clock',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_converted')
                    ->label('Expense Ledger')
                    ->badge()
                    ->formatStateUsing(fn (bool $state) => $state ? 'Posted to Expenses' : 'Not Posted')
                    ->color(fn (bool $state) => $state ? 'info' : 'gray'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Approval',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('currency')
                    ->options([
                        'UGX' => 'UGX',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'KES' => 'KES',
                    ]),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Requester')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Requisition $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('remarks')
                            ->label('Approval Remarks / Notes')
                            ->placeholder('e.g. Budget approved under Q4 logistics allocation.')
                            ->default('Approved'),
                    ])
                    ->action(function (Requisition $record, array $data): void {
                        RequisitionApprover::updateOrCreate(
                            ['requisition_id' => $record->id, 'user_id' => Auth::id()],
                            ['status' => 'approved', 'remarks' => $data['remarks'] ?? 'Approved']
                        );
                        $record->update(['status' => 'approved']);
                        $record->invalidateCache();

                        Notification::make()
                            ->title('Requisition Approved')
                            ->body("Requisition REQ-" . str_pad($record->id, 4, '0', STR_PAD_LEFT) . " has been approved successfully.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Requisition $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('remarks')
                            ->label('Reason for Rejection')
                            ->required()
                            ->placeholder('Specify why this requisition is rejected...'),
                    ])
                    ->action(function (Requisition $record, array $data): void {
                        RequisitionApprover::updateOrCreate(
                            ['requisition_id' => $record->id, 'user_id' => Auth::id()],
                            ['status' => 'rejected', 'remarks' => $data['remarks']]
                        );
                        $record->update(['status' => 'rejected']);
                        $record->invalidateCache();

                        Notification::make()
                            ->title('Requisition Rejected')
                            ->body("Requisition REQ-" . str_pad($record->id, 4, '0', STR_PAD_LEFT) . " has been rejected.")
                            ->danger()
                            ->send();
                    }),

                Tables\Actions\Action::make('disburse')
                    ->label('Disburse / Payout')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Requisition $record) => $record->status === 'approved' && $record->balance > 0)
                    ->form(function (Requisition $record) {
                        return [
                            Forms\Components\Select::make('requisition_detail_id')
                                ->label('Expenditure Line Item')
                                ->options($record->details->pluck('particular', 'id'))
                                ->required()
                                ->placeholder('Select item to disburse for'),
                            Forms\Components\DatePicker::make('payment_date')
                                ->label('Payment Date')
                                ->default(now())
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->label('Amount to Disburse')
                                ->numeric()
                                ->required()
                                ->default($record->balance)
                                ->maxValue($record->balance)
                                ->prefix($record->currency),
                            Forms\Components\Select::make('supplier_id')
                                ->label('Payee / Supplier')
                                ->options(Supplier::all()->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone_number')
                                        ->tel()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('address')
                                        ->maxLength(255),
                                ]),
                            Forms\Components\Select::make('mode_of_payment')
                                ->label('Payment Channel')
                                ->options([
                                    'Cash' => 'Cash',
                                    'Bank Transfer' => 'Bank Transfer',
                                    'Mobile Money' => 'Mobile Money',
                                    'Digital' => 'Digital / Card',
                                ])
                                ->default('Cash')
                                ->required(),
                            Forms\Components\Select::make('payment_account_id')
                                ->label('Source Payment Account (Ledger)')
                                ->options(PaymentAccount::all()->pluck('name', 'id'))
                                ->searchable()
                                ->placeholder('Select account to deduct from (optional)'),
                            Forms\Components\TextInput::make('particular')
                                ->label('Payment Narrative / Ref')
                                ->placeholder('e.g. Cash disbursement voucher for logistics handling'),
                        ];
                    })
                    ->action(function (Requisition $record, array $data): void {
                        $payout = RequisitionPayment::create([
                            'supplier_id' => $data['supplier_id'],
                            'user_id' => Auth::id(),
                            'requisition_detail_id' => $data['requisition_detail_id'],
                            'payment_date' => $data['payment_date'],
                            'amount' => $data['amount'],
                            'mode_of_payment' => $data['mode_of_payment'],
                            'currency' => $record->currency ?? 'UGX',
                            'particular' => $data['particular'] ?? ('Payout for REQ-' . $record->id),
                        ]);

                        if (!empty($data['payment_account_id'])) {
                            Transaction::saveTransaction(
                                $data['payment_account_id'],
                                $payout->amount,
                                $payout->payment_date,
                                Auth::id(),
                                null,
                                'debit',
                                $payout->particular
                            );
                        }

                        $record->invalidateCache();

                        Notification::make()
                            ->title('Disbursement Recorded')
                            ->body("Successfully disbursed {$record->currency} " . number_format($data['amount']) . " for REQ-" . str_pad($record->id, 4, '0', STR_PAD_LEFT))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('convertToExpense')
                    ->label('Post to Expenses')
                    ->icon('heroicon-o-arrow-up-right')
                    ->color('info')
                    ->visible(fn (Requisition $record) => $record->status === 'approved' && !$record->is_converted)
                    ->requiresConfirmation()
                    ->modalHeading('Post Requisition to Expenses Ledger')
                    ->modalDescription('This will record all line items of this approved requisition into the company expense ledger.')
                    ->modalSubmitActionLabel('Confirm Posting')
                    ->action(function (Requisition $record): void {
                        foreach ($record->details as $item) {
                            Expense::create([
                                'user_id' => Auth::id(),
                                'requisition_id' => $record->id,
                                'particular' => $item->particular,
                                'reason' => $item->reason,
                                'quantity' => $item->quantity,
                                'estimated_cost' => $item->estimated_cost,
                            ]);
                        }

                        Notification::make()
                            ->title('Expenses Posted')
                            ->body("Requisition REQ-" . str_pad($record->id, 4, '0', STR_PAD_LEFT) . " items posted to company expense ledger.")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('print')
                    ->label('Print Voucher')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Requisition $record) => route('requisitions.secure.print', $record->hashed_id))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Requisition $record) => route('requisitions.secure.pdf', $record->hashed_id)),

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
            'index' => Pages\ListRequisitions::route('/'),
            'create' => Pages\CreateRequisition::route('/create'),
            'edit' => Pages\EditRequisition::route('/{record}/edit'),
        ];
    }
}
