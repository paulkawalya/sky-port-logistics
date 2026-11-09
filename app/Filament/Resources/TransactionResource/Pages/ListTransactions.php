<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\TransactionResource\Widgets\TransactionOverviewWidget;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    public ?string $activeTab = 'all';

    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Transaction')
                ->icon('heroicon-o-plus-circle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionOverviewWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Ledger Entries'),
            'credits' => Tab::make('Credits (Inflow)')
                ->badge(fn () => Transaction::where('transaction_type', 'credit')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('transaction_type', 'credit')),
            'debits' => Tab::make('Debits (Outflow)')
                ->badge(fn () => Transaction::where('transaction_type', 'debit')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('transaction_type', 'debit')),
        ];
    }
}
