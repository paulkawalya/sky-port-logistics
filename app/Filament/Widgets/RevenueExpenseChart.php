<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\InvoiceDetail;
use App\Models\QuotationDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue vs. Operational Expenses (Last 6 Months)';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '290px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 'full',
        'xl' => 2,
    ];

    protected function getData(): array
    {
        $months = [];
        $revenueData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $months[] = $monthName;

            // Monthly Invoiced Revenue (Freight Invoices + Manual Invoices)
            $monthlyFreightRevenue = InvoiceDetail::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum(DB::raw('quantity * unit_price'));

            $monthlyManualRevenue = QuotationDetail::whereHas('quotation', fn ($q) => $q->where('transaction_type', 'invoice'))
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum(DB::raw('quantity * unit_price'));

            $monthlyRevenue = $monthlyFreightRevenue + $monthlyManualRevenue;

            // Monthly Operational Expenses
            $monthlyExpense = Expense::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('estimated_cost');

            $revenueData[] = round($monthlyRevenue, 2);
            $expenseData[] = round($monthlyExpense, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Billed Revenue ($)',
                    'data' => $revenueData,
                    'borderColor' => '#ed1c24',
                    'backgroundColor' => 'rgba(237, 28, 36, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Operational Expenses ($)',
                    'data' => $expenseData,
                    'borderColor' => '#64748b',
                    'backgroundColor' => 'rgba(100, 116, 139, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
