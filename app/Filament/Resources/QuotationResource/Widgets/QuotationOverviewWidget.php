<?php

namespace App\Filament\Resources\QuotationResource\Widgets;

use App\Models\Quotation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuotationOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $quotes = Quotation::where(function ($q) {
            $q->whereNull('transaction_type')
              ->orWhere('transaction_type', '!=', 'invoice');
        })->with('details')->get();

        $totalValue = $quotes->sum(fn ($q) => $q->total_amount);
        $totalCount = $quotes->count();
        $convertedCount = Quotation::where('transaction_type', 'invoice')->count();
        $avgValue = $totalCount > 0 ? ($totalValue / $totalCount) : 0;

        return [
            Stat::make('Total Quotations Value', '$' . number_format($totalValue, 2))
                ->description(number_format($totalCount) . ' active proformas/quotes')
                ->descriptionIcon('heroicon-m-document-currency-dollar')
                ->chart([8, 15, 22, 35, 45, max((int)($totalValue / 1000), 20)])
                ->color('primary'),

            Stat::make('Quotations Count', number_format($totalCount))
                ->description('Pending client confirmation')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([3, 5, 4, 6, 8, max($totalCount, 4)])
                ->color('info'),

            Stat::make('Converted to Invoices', number_format($convertedCount))
                ->description('Converted to billing invoices')
                ->descriptionIcon('heroicon-m-arrow-right-circle')
                ->chart([2, 4, 3, 7, 6, max($convertedCount, 3)])
                ->color('success'),

            Stat::make('Average Quote Value', '$' . number_format($avgValue, 2))
                ->description('Per freight quotation')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart([10, 12, 15, 14, 18, max((int)($avgValue / 500), 10)])
                ->color('warning'),
        ];
    }
}

