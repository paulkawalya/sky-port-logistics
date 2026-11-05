<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequisitionResource\Pages;
use App\Filament\Resources\RequisitionResource\RelationManagers;
use App\Models\Requisition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequisitionResource extends Resource
{
    protected static ?string $model = Requisition::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Requisition Header')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Requested By')
                            ->default(fn () => auth()->id())
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
                            ->label('General Purpose / Remarks')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Requisition Line Items (Budget Breakdown)')
                    ->description('Specify each expenditure item, quantity, unit cost, and category')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\TextInput::make('particular')
                                    ->label('Item / Expense Description')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\Select::make('expense_category_id')
                                    ->relationship('category', 'name')
                                    ->label('Category')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty')
                                    ->default('1')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('estimated_cost')
                                    ->label('Unit Cost')
                                    ->numeric()
                                    ->prefix('$ / Shs')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('reason')
                                    ->label('Justification / Details')
                                    ->columnSpan(1),
                            ])
                            ->columns(6)
                            ->defaultItems(1)
                            ->reorderableWithButtons()
                            ->addActionLabel('Add Item to Requisition')
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#REQ')
                    ->prefix('REQ-')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Requested By')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_requisitioned')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Cost')
                    ->state(fn ($record) => $record->details()->sum(\DB::raw('quantity * estimated_cost')))
                    ->money(fn ($record) => $record->currency === 'USD' ? 'USD' : 'UGX')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending' && in_array(auth()->user()?->user_type, ['Admin', 'MD', 'Manager']))
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending' && in_array(auth()->user()?->user_type, ['Admin', 'MD', 'Manager']))
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),
                Tables\Actions\Action::make('convertToExpense')
                    ->label('Disburse / Post Expense')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'approved' && in_array(auth()->user()?->user_type, ['Admin', 'Accountant', 'MD', 'Manager']))
                    ->requiresConfirmation()
                    ->modalHeading('Post Requisition to Expenses Ledger')
                    ->modalDescription('This will convert all approved items in this requisition into confirmed business expense entries.')
                    ->action(function ($record) {
                        foreach ($record->details as $item) {
                            \App\Models\Expense::create([
                                'user_id' => auth()->id(),
                                'requisition_id' => $record->id,
                                'particular' => $item->particular,
                                'reason' => $item->reason,
                                'quantity' => $item->quantity,
                                'estimated_cost' => $item->estimated_cost,
                            ]);
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Expenses Posted')
                            ->body('Requisition items have been posted to the Expense Ledger successfully.')
                            ->success()
                            ->send();
                    }),
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
