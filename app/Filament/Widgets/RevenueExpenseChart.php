<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\InvoiceDetail;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue vs. Expenses (Last 6 Months)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = [];
        $revenueData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $months[] = $monthName;

            // Monthly Invoiced Revenue (Quantity * Unit Price)
            $monthlyRevenue = InvoiceDetail::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum(\DB::raw('quantity * unit_price'));

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
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ],
                [
                    'label' => 'Operational Expenses ($)',
                    'data' => $expenseData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'fill' => true,
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
