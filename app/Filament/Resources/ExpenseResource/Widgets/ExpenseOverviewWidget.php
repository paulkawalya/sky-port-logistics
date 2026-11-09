<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExpenseOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $all = Expense::all();
        $totalCost = $all->sum('estimated_cost');
        $thisMonth = Expense::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('estimated_cost');
        $totalCount = $all->count();
        $avgCost = $totalCount > 0 ? ($totalCost / $totalCount) : 0;

        return [
            Stat::make('Total Operations Cost', '$' . number_format($totalCost, 2))
                ->description(number_format($totalCount) . ' recorded line items')
                ->descriptionIcon('heroicon-m-credit-card')
                ->chart([10, 20, 30, 45, 55, max((int)($totalCost / 500), 20)])
                ->color('primary'),

            Stat::make('Expenses This Month', '$' . number_format($thisMonth, 2))
                ->description(now()->format('F Y') . ' operational outlay')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([5, 12, 18, 25, 30, max((int)($thisMonth / 500), 10)])
                ->color('warning'),

            Stat::make('Average Expense', '$' . number_format($avgCost, 2))
                ->description('Per operational cost item')
                ->descriptionIcon('heroicon-m-calculator')
                ->chart([8, 12, 10, 14, 12, max((int)($avgCost / 200), 8)])
                ->color('info'),

            Stat::make('Total Line Items', number_format($totalCount))
                ->description('Freight, clearing & terminal costs')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->chart([2, 5, 8, 12, 15, max($totalCount, 5)])
                ->color('success'),
        ];
    }
}
