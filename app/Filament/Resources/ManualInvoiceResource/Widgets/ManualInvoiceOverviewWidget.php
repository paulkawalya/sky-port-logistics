<?php

namespace App\Filament\Resources\ManualInvoiceResource\Widgets;

use App\Models\Quotation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ManualInvoiceOverviewWidget extends BaseWidget
{
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $invoices = Quotation::where('transaction_type', 'invoice')->with(['details', 'payments'])->get();
        
        $totalBilled = $invoices->sum(fn ($i) => $i->total_amount);
        $totalPaid = $invoices->sum(fn ($i) => $i->payments->sum('amount_paid'));
        $totalOutstanding = $invoices->sum(fn ($i) => $i->balance);
        $settledCount = $invoices->filter(fn ($i) => $i->balance <= 0 && $i->total_amount > 0)->count();

        return [
            Stat::make('Total Invoiced', '$' . number_format($totalBilled, 2))
                ->description(count($invoices) . ' commercial invoice' . (count($invoices) !== 1 ? 's' : ''))
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([10, 20, 35, 50, 70, max((int)($totalBilled / 1000), 25)])
                ->color('primary'),

            Stat::make('Collected Revenue', '$' . number_format($totalPaid, 2))
                ->description('Settled customer payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([5, 15, 25, 40, 55, max((int)($totalPaid / 1000), 15)])
                ->color('success'),

            Stat::make('Outstanding Receivables', '$' . number_format($totalOutstanding, 2))
                ->description($totalOutstanding > 0 ? 'Pending client settlement' : 'Zero overdue balance')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([15, 12, 18, 14, 16, max((int)($totalOutstanding / 1000), 10)])
                ->color($totalOutstanding > 0 ? 'danger' : 'success'),

            Stat::make('Settled Invoices', number_format($settledCount))
                ->description('Zero remaining balance')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([2, 4, 6, 8, 10, max($settledCount, 3)])
                ->color('info'),
        ];
    }
}

