<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Finance & Accounting';

    protected static ?string $navigationLabel = 'Quotations';

    protected static ?string $modelLabel = 'Quotation';

    protected static ?string $pluralModelLabel = 'Quotations';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('transaction_type', 'quotation')
            ->with(['customer', 'details']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quotation Header')
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
                        Forms\Components\Hidden::make('transaction_type')
                            ->default('quotation'),
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => Auth::id()),
                    ])->columns(3),

                Forms\Components\Section::make('Cargo & Transport Specifications')
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
                            ->label('Packages')
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
                            ->label('Quotation Description / Scope of Work')
                            ->placeholder('Enter estimate details, transit route, or notes...')
                            ->columnSpanFull(),
                    ])->columns(3)->collapsible(),

                Forms\Components\Section::make('Quotation Line Items')
                    ->description('Specify items, shipping rates, and estimated costs.')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->relationship('details')
                            ->schema([
                                Forms\Components\TextInput::make('particular')
                                    ->label('Description / Particular')
                                    ->required()
                                    ->placeholder('e.g. Freight handling fee / Inspection charge')
                                    ->columnSpan(6),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Estimated Unit Price')
                                    ->numeric()
                                    ->prefix(fn (callable $get) => $get('../../transaction_currency') === 'UGX' ? 'UGX' : '$')
                                    ->required()
                                    ->columnSpan(4),
                            ])
                            ->columns(12)
                            ->defaultItems(1)
                            ->addActionLabel('Add Item to Quotation'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Quote #')
                    ->formatStateUsing(fn ($state) => 'QTN-' . str_pad($state, 4, '0', STR_PAD_LEFT))
                    ->weight('bold')
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
                    ->label('Container/AWB')
                    ->placeholder('N/A')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->label('Currency')
                    ->badge()
                    ->color(fn ($state) => $state === 'USD' ? 'success' : 'primary'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Estimate')
                    ->money(fn (Quotation $record) => $record->transaction_currency ?? 'USD')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('packages')
                    ->label('Pkgs')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight (KG)')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\Action::make('convertToInvoice')
                    ->label('Convert to Manual Invoice')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Convert Quotation to Manual Invoice')
                    ->modalDescription('This will convert this quotation into an active billing manual invoice and move it to the Manual Invoices ledger.')
                    ->modalSubmitActionLabel('Yes, Convert to Invoice')
                    ->action(function (Quotation $record): void {
                        $record->update(['transaction_type' => 'invoice']);

                        Notification::make()
                            ->title('Quotation Converted to Invoice')
                            ->body("Quotation has been successfully converted to Manual Invoice #INV-M-" . str_pad($record->id, 4, '0', STR_PAD_LEFT))
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
