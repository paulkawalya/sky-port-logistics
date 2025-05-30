<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\PaymentAccount;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Display the finance dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // Get all available currencies
        $currencies = PaymentAccount::distinct('transaction_currency')->pluck('transaction_currency')->toArray();

        // Default to showing USD currency
        $selectedCurrency = request('currency', 'USD');

        // Get summary data for regular invoices
        $invoices = Invoice::all();
        $regularTotalInvoices = $invoices->count();
        $regularPaidInvoices = $invoices->filter(function($invoice) {
            return $invoice->payment_status === 'paid';
        })->count();
        $regularPendingInvoices = $invoices->filter(function($invoice) {
            return $invoice->payment_status === 'not paid';
        })->count();

        // Get summary data for manual invoices (quotations of type 'invoice')
        $manualInvoices = Quotation::where('transaction_type', 'invoice')->get();
        $manualTotalInvoices = $manualInvoices->count();
        $manualPaidInvoices = $manualInvoices->filter(function($invoice) {
            return $invoice->balance($invoice->id) <= 0 && $invoice->paid($invoice->id) > 0;
        })->count();
        $manualPendingInvoices = $manualInvoices->filter(function($invoice) {
            return $invoice->balance($invoice->id) > 0;
        })->count();

        // Combined totals
        $totalInvoices = $regularTotalInvoices + $manualTotalInvoices;
        $paidInvoices = $regularPaidInvoices + $manualPaidInvoices;
        $pendingInvoices = $regularPendingInvoices + $manualPendingInvoices;

        // Get financial data by currency
        $financialData = [];
        $totalRevenue = 0;
        $totalExpenses = 0;
        $netProfit = 0;

        foreach ($currencies as $currency) {
            // Get accounts for this currency
            $accountIds = PaymentAccount::where('transaction_currency', $currency)->pluck('id')->toArray();

            // Get transactions for these accounts
            $currencyRevenue = Transaction::whereIn('payment_account_id', $accountIds)
                ->where('transaction_type', 'credit')
                ->sum('amount');

            $currencyExpenses = Transaction::whereIn('payment_account_id', $accountIds)
                ->where('transaction_type', 'debit')
                ->sum('amount');

            $currencyProfit = $currencyRevenue - $currencyExpenses;

            $financialData[$currency] = [
                'revenue' => $currencyRevenue,
                'expenses' => $currencyExpenses,
                'profit' => $currencyProfit
            ];

            // Add to totals if this is the selected currency or if showing all
            if ($selectedCurrency === 'all' || $selectedCurrency === $currency) {
                $totalRevenue += $currencyRevenue;
                $totalExpenses += $currencyExpenses;
                $netProfit += $currencyProfit;
            }
        }

        // Get account balances
        $accountBalances = PaymentAccount::select('id', 'name', 'transaction_currency as currency')
            ->when($selectedCurrency !== 'all', function($query) use ($selectedCurrency) {
                return $query->where('transaction_currency', $selectedCurrency);
            })
            ->get();

        // Get monthly data for charts based on selected currency
        $monthlyRevenue = $this->getMonthlyData('revenue', $selectedCurrency);
        $monthlyExpenses = $this->getMonthlyData('expenses', $selectedCurrency);
        $monthlyProfit = $this->getMonthlyData('profit', $selectedCurrency);

        return view('finance.dashboard', compact(
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'regularTotalInvoices',
            'regularPaidInvoices',
            'regularPendingInvoices',
            'manualTotalInvoices',
            'manualPaidInvoices',
            'manualPendingInvoices',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'accountBalances',
            'monthlyRevenue',
            'monthlyExpenses',
            'monthlyProfit',
            'currencies',
            'selectedCurrency',
            'financialData'
        ));
    }

    /**
     * Get dashboard data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dashboardData(Request $request)
    {
        $period = $request->input('period', 'year');
        $startDate = null;
        $endDate = Carbon::now();

        switch ($period) {
            case 'month':
                $startDate = Carbon::now()->subMonth();
                break;
            case 'quarter':
                $startDate = Carbon::now()->subMonths(3);
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                break;
            default:
                $startDate = Carbon::now()->subYear();
        }

        $revenueData = $this->getRevenueData($startDate, $endDate);
        $expenseData = $this->getExpenseData($startDate, $endDate);
        $profitData = $this->getProfitData($revenueData, $expenseData);
        $topExpenseCategories = $this->getTopExpenseCategories($startDate, $endDate);
        $recentTransactions = $this->getRecentTransactions();

        return response()->json([
            'revenue' => $revenueData,
            'expenses' => $expenseData,
            'profit' => $profitData,
            'topExpenseCategories' => $topExpenseCategories,
            'recentTransactions' => $recentTransactions
        ]);
    }

    /**
     * Export dashboard data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportDashboard(Request $request)
    {
        $format = $request->input('format', 'pdf');
        $period = $request->input('period', 'year');

        // Get data for export
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();

        $data = [
            'totalInvoices' => Invoice::count(),
            'paidInvoices' => Invoice::where('payment_status', 'paid')->count(),
            'pendingInvoices' => Invoice::where('payment_status', 'not paid')->count(),
            'totalRevenue' => InvoicePayment::sum('amount_paid'),
            'totalExpenses' => Expense::sum('estimated_cost'),
            'netProfit' => InvoicePayment::sum('amount_paid') - Expense::sum('estimated_cost'),
            'revenueData' => $this->getRevenueData($startDate, $endDate),
            'expenseData' => $this->getExpenseData($startDate, $endDate),
            'topExpenseCategories' => $this->getTopExpenseCategories($startDate, $endDate),
        ];

        if ($format === 'pdf') {
            // Generate PDF
            $pdf = \PDF::loadView('finance.exports.dashboard_pdf', $data);
            return $pdf->download('finance_dashboard_' . Carbon::now()->format('Y-m-d') . '.pdf');
        } else {
            // Generate Excel
            return Excel::download(new FinanceDashboardExport($data), 'finance_dashboard_' . Carbon::now()->format('Y-m-d') . '.xlsx');
        }
    }

    /**
     * Get monthly data for charts.
     *
     * @param  string  $type
     * @param  string  $currency
     * @return array
     */
    private function getMonthlyData($type, $currency = 'all')
    {
        $currentYear = Carbon::now()->year;
        $months = [];
        $values = [];

        // Get account IDs for the selected currency
        $accountIds = [];
        if ($currency !== 'all') {
            $accountIds = PaymentAccount::where('transaction_currency', $currency)
                ->pluck('id')
                ->toArray();
        }

        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::createFromDate($currentYear, $i, 1);
            $months[] = $month->format('M');

            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            if ($type === 'revenue') {
                if ($currency === 'all') {
                    $value = Transaction::where('transaction_type', 'credit')
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                } else {
                    $value = Transaction::where('transaction_type', 'credit')
                        ->whereIn('payment_account_id', $accountIds)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                }
            } elseif ($type === 'expenses') {
                if ($currency === 'all') {
                    $value = Transaction::where('transaction_type', 'debit')
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                } else {
                    $value = Transaction::where('transaction_type', 'debit')
                        ->whereIn('payment_account_id', $accountIds)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                }
            } elseif ($type === 'profit') {
                if ($currency === 'all') {
                    $revenue = Transaction::where('transaction_type', 'credit')
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                    $expenses = Transaction::where('transaction_type', 'debit')
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                } else {
                    $revenue = Transaction::where('transaction_type', 'credit')
                        ->whereIn('payment_account_id', $accountIds)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                    $expenses = Transaction::where('transaction_type', 'debit')
                        ->whereIn('payment_account_id', $accountIds)
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->sum('amount');
                }
                $value = $revenue - $expenses;
            } else {
                $value = 0;
            }

            $values[] = $value;
        }

        return [
            'labels' => $months,
            'values' => $values
        ];
    }

    /**
     * Get revenue data for the specified period.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getRevenueData($startDate, $endDate)
    {
        $revenue = InvoicePayment::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(amount_paid) as total')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'labels' => $revenue->pluck('date')->toArray(),
            'values' => $revenue->pluck('total')->toArray()
        ];
    }

    /**
     * Get expense data for the specified period.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getExpenseData($startDate, $endDate)
    {
        $expenses = Expense::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(estimated_cost) as total')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'labels' => $expenses->pluck('date')->toArray(),
            'values' => $expenses->pluck('total')->toArray()
        ];
    }

    /**
     * Calculate profit data from revenue and expense data.
     *
     * @param  array  $revenueData
     * @param  array  $expenseData
     * @return array
     */
    private function getProfitData($revenueData, $expenseData)
    {
        $allDates = array_unique(array_merge($revenueData['labels'], $expenseData['labels']));
        sort($allDates);

        $profitValues = [];

        foreach ($allDates as $date) {
            $revenueIndex = array_search($date, $revenueData['labels']);
            $expenseIndex = array_search($date, $expenseData['labels']);

            $revenue = $revenueIndex !== false ? $revenueData['values'][$revenueIndex] : 0;
            $expense = $expenseIndex !== false ? $expenseData['values'][$expenseIndex] : 0;

            $profitValues[] = $revenue - $expense;
        }

        return [
            'labels' => $allDates,
            'values' => $profitValues
        ];
    }

    /**
     * Get top expense categories.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    private function getTopExpenseCategories($startDate, $endDate)
    {
        $categories = Expense::select(
            'expense_categories.name',
            DB::raw('SUM(expenses.amount) as total')
        )
        ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
        ->whereBetween('expenses.created_at', [$startDate, $endDate])
        ->groupBy('expense_categories.name')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'values' => $categories->pluck('total')->toArray()
        ];
    }

    /**
     * Get recent transactions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecentTransactions()
    {
        return Transaction::with(['account', 'related_account'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
}
