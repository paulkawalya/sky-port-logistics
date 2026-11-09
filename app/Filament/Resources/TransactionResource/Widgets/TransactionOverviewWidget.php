<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $credits = Transaction::where('transaction_type', 'credit')->sum('amount');
        $debits = Transaction::where('transaction_type', 'debit')->sum('amount');
        $net = $credits - $debits;
        $totalCount = Transaction::count();

        return [
            Stat::make('Total Inflows (Credits)', '$' . number_format($credits, 2))
                ->description('Received payments & deposits')
                ->descriptionIcon('heroicon-m-arrow-down-left')
                ->chart([10, 20, 35, 45, 60, max((int)($credits / 1000), 20)])
                ->color('success'),

            Stat::make('Total Outflows (Debits)', '$' . number_format($debits, 2))
                ->description('Disbursements & operational costs')
                ->descriptionIcon('heroicon-m-arrow-up-right')
                ->chart([5, 12, 20, 30, 40, max((int)($debits / 1000), 15)])
                ->color('danger'),

            Stat::make('Net Cash Position', '$' . number_format($net, 2))
                ->description($net >= 0 ? 'Positive net treasury' : 'Net cash deficit')
                ->descriptionIcon('heroicon-m-scale')
                ->chart([15, 10, 20, 15, 25, max((int)abs($net / 1000), 10)])
                ->color($net >= 0 ? 'primary' : 'warning'),

            Stat::make('Ledger Entries', number_format($totalCount))
                ->description('Audited account journal records')
                ->descriptionIcon('heroicon-m-book-open')
                ->chart([2, 5, 8, 12, 16, max($totalCount, 5)])
                ->color('info'),
        ];
    }
}
