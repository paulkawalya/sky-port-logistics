<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvoiceOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $invoices = Invoice::with(['details', 'payments'])->get();
        
        $totalBilled = $invoices->sum(fn ($i) => $i->details->sum(fn ($d) => $d->quantity * $d->unit_price));
        $totalPaid = $invoices->sum(fn ($i) => $i->payments->sum('amount_paid'));
        $totalOutstanding = max(0, $totalBilled - $totalPaid);
        $totalCount = $invoices->count();

        return [
            Stat::make('Total Freight Billed', '$' . number_format($totalBilled, 2))
                ->description(number_format($totalCount) . ' freight invoices')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart([10, 20, 30, 45, 60, max((int)($totalBilled / 1000), 20)])
                ->color('primary'),

            Stat::make('Freight Revenue Paid', '$' . number_format($totalPaid, 2))
                ->description('Received payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([5, 12, 22, 35, 50, max((int)($totalPaid / 1000), 15)])
                ->color('success'),

            Stat::make('Unpaid Freight Balance', '$' . number_format($totalOutstanding, 2))
                ->description($totalOutstanding > 0 ? 'Pending client settlement' : 'Zero overdue balance')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([8, 14, 12, 16, 15, max((int)($totalOutstanding / 1000), 8)])
                ->color($totalOutstanding > 0 ? 'danger' : 'success'),

            Stat::make('Invoices Generated', number_format($totalCount))
                ->description('Active ocean & air billings')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->chart([2, 4, 3, 5, 6, max($totalCount, 3)])
                ->color('info'),
        ];
    }
}
