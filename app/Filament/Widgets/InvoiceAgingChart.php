<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Quotation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class InvoiceAgingChart extends ChartWidget
{
    protected static ?string $heading = 'Unpaid Receivables Aging';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '290px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected function getData(): array
    {
        $now = Carbon::now();

        // Buckets for outstanding balances
        $buckets = [
            'Current (0-30d)' => 0,
            '31-60 Days' => 0,
            '61-90 Days' => 0,
            'Over 90 Days' => 0,
        ];

        // Retrieve freight invoices with details and payments
        $invoices = Invoice::with(['details', 'payments'])->get();

        foreach ($invoices as $invoice) {
            $totalBilled = $invoice->details->sum(fn ($d) => $d->quantity * $d->unit_price);
            $totalPaid = $invoice->payments->sum('amount_paid');
            $outstanding = max(0, $totalBilled - $totalPaid);

            if ($outstanding <= 0) {
                continue;
            }

            $invoiceDate = Carbon::parse($invoice->date_invoiced ?? $invoice->created_at);
            $ageInDays = $invoiceDate->diffInDays($now);

            if ($ageInDays <= 30) {
                $buckets['Current (0-30d)'] += $outstanding;
            } elseif ($ageInDays <= 60) {
                $buckets['31-60 Days'] += $outstanding;
            } elseif ($ageInDays <= 90) {
                $buckets['61-90 Days'] += $outstanding;
            } else {
                $buckets['Over 90 Days'] += $outstanding;
            }
        }

        // Include manual invoices as well
        $manualInvoices = Quotation::where('transaction_type', 'invoice')
            ->with(['details', 'payments'])
            ->get();

        foreach ($manualInvoices as $manualInvoice) {
            $outstanding = $manualInvoice->balance;

            if ($outstanding <= 0) {
                continue;
            }

            $invoiceDate = Carbon::parse($manualInvoice->date_quoted ?? $manualInvoice->created_at);
            $ageInDays = $invoiceDate->diffInDays($now);

            if ($ageInDays <= 30) {
                $buckets['Current (0-30d)'] += $outstanding;
            } elseif ($ageInDays <= 60) {
                $buckets['31-60 Days'] += $outstanding;
            } elseif ($ageInDays <= 90) {
                $buckets['61-90 Days'] += $outstanding;
            } else {
                $buckets['Over 90 Days'] += $outstanding;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Unpaid ($)',
                    'data' => array_values($buckets),
                    'backgroundColor' => [
                        '#10b981', // green for 0-30
                        '#f59e0b', // amber for 31-60
                        '#f97316', // orange for 61-90
                        '#ed1c24', // brand red for 90+
                    ],
                ],
            ],
            'labels' => array_keys($buckets),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
