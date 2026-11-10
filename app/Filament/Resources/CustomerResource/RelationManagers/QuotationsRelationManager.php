<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Models\Quotation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuotationsRelationManager extends RelationManager
{
    protected static string $relationship = 'quotations';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Quotations & Inquiries';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_quoted')
                    ->label('Quote Date')
                    ->default(now())
                    ->required(),
                Forms\Components\Select::make('transaction_currency')
                    ->options([
                        'USD' => 'USD ($)',
                        'UGX' => 'UGX (Shs)',
                    ])
                    ->default('USD')
                    ->required(),
                Forms\Components\TextInput::make('container_air_no')
                    ->label('Container / Flight #'),
                Forms\Components\Textarea::make('description')
                    ->label('Scope of Work / Description')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Quote #')
                    ->prefix('QT-')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_quoted')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_currency')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Estimated Total')
                    ->money(fn ($record) => $record->transaction_currency ?? 'USD')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Invoiced' => 'success',
                        'Pending' => 'warning',
                        'Cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Quotation')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Tables\Actions\Action::make('convertToInvoice')
                    ->label('Convert to Invoice')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->visible(fn (Quotation $record) => $record->status !== 'Invoiced')
                    ->requiresConfirmation()
                    ->action(function (Quotation $record): void {
                        $record->status = 'Invoiced';
                        $record->save();

                        Notification::make()
                            ->title('Quotation Converted')
                            ->body("Quotation QT-{$record->id} converted to an active billable record.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Quotation $record) => route('quotations.print', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }
}

