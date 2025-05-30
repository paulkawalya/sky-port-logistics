<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Transit;
use App\Models\Customer;
use App\Models\QuotationPayment;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Transaction;
use App\Models\PaymentAccount;
use App\Models\Requisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get start and end dates from request or default to last 30 days
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30);
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now();

        // Invoices aging report
        $agingReport = Quotation::with(['details', 'payments'])
            ->select('quotations.*')
            ->selectRaw('
                CASE
                    WHEN DATEDIFF(NOW(), created_at) <= 30 THEN "0-30 days"
                    WHEN DATEDIFF(NOW(), created_at) <= 60 THEN "31-60 days"
                    WHEN DATEDIFF(NOW(), created_at) <= 90 THEN "61-90 days"
                    ELSE "Over 90 days"
                END as aging_period
            ')
            ->get()
            ->groupBy('aging_period')
            ->map(function($group) {
                return (object)[
                    'aging_period' => $group->first()->aging_period,
                    'invoice_count' => $group->count(),
                    'total_amount' => $group->sum(function($q) { return $q->totalAmount($q->id); }),
                    'amount_paid' => $group->sum(function($q) { return $q->paid($q->id); }),
                    'balance' => $group->sum(function($q) { return $q->balance($q->id); })
                ];
            })
            ->values();

        // Monthly collections for chart
        $monthlyCollections = QuotationPayment::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(amount_paid) as total_collected')
        )
        ->whereBetween('created_at', [Carbon::now()->subMonths(11), Carbon::now()])
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top customers by revenue
        $topCustomers = Quotation::with(['customer', 'payments'])
            ->select('quotations.customer_id')
            ->selectRaw('COUNT(DISTINCT quotations.id) as invoice_count')
            ->join('quotation_payments', 'quotations.id', '=', 'quotation_payments.quotation_id')
            ->whereBetween('quotation_payments.created_at', [$startDate, $endDate])
            ->groupBy('customer_id')
            ->get()
            ->map(function($item) {
                return (object)[
                    'customer' => $item->customer,
                    'invoice_count' => $item->invoice_count,
                    'total_revenue' => $item->payments->sum('amount_paid')
                ];
            })
            ->sortByDesc('total_revenue')
            ->take(5)
            ->values();

        // Container statistics
        $containerStats = Transit::select(
            DB::raw('COUNT(*) as total_containers')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->first();

        // Add calculated properties
        $containerStats->in_transit = Transit::whereHas('statuses', function($query) {
            $query->whereNotIn('status', ['Delivered', 'Completed']);
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        $containerStats->completed = Transit::whereHas('statuses', function($query) {
            $query->whereIn('status', ['Delivered', 'Completed']);
        })->whereBetween('created_at', [$startDate, $endDate])->count();

        $containerStats->avg_transit_days = 0; // Default value

        // Amount collected metrics
        $amountCollectedToday = QuotationPayment::whereDate('created_at', Carbon::today())
            ->sum('amount_paid');

        $amountCollectedLast7Days = QuotationPayment::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('amount_paid');

        $amountCollectedCustomPeriod = QuotationPayment::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_paid');

        // Payment statistics
        $paymentStats = Quotation::with(['payments'])
            ->get()
            ->map(function($quotation) {
                $total = $quotation->totalAmount($quotation->id);
                $paid = $quotation->paid($quotation->id);
                $balance = $quotation->balance($quotation->id);

                if ($balance <= 0) {
                    $status = 'Paid';
                } elseif ($paid > 0) {
                    $status = 'Partial';
                } else {
                    $status = 'Unpaid';
                }

                return [
                    'status' => $status,
                    'total_amount' => $total,
                    'paid_amount' => $paid,
                    'balance' => $balance
                ];
            })
            ->groupBy('status')
            ->map(function($group) {
                return [
                    'status' => $group->first()['status'],
                    'count' => $group->count(),
                    'total_amount' => $group->sum('total_amount'),
                    'paid_amount' => $group->sum('paid_amount'),
                    'balance' => $group->sum('balance')
                ];
            })
            ->values();

        // Containers in transit
        $containersInTransit = Transit::with(['customer'])
            ->whereHas('statuses', function($query) {
                $query->whereNotIn('status', ['Delivered', 'Completed']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Popular routes - placeholder since origin and destination columns don't exist
        $popularRoutes = collect([]);

        return view('reports.dashboard', compact(
            'agingReport',
            'amountCollectedToday',
            'amountCollectedLast7Days',
            'amountCollectedCustomPeriod',
            'containersInTransit',
            'startDate',
            'endDate',
            'monthlyCollections',
            'topCustomers',
            'containerStats',
            'popularRoutes',
            'paymentStats'
        ));
    }

    /**
     * Display the financial reports page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function financial(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        // Get all payment accounts for filtering
        $accounts = PaymentAccount::all();

        // Get financial summary
        $totalRevenue = InvoicePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount_paid');
        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('estimated_cost');
        $netProfit = $totalRevenue - $totalExpenses;

        // Get expense categories for filtering
        $expenseCategories = ExpenseCategory::all();

        return view('reports.financial', compact(
            'startDate',
            'endDate',
            'accounts',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'expenseCategories'
        ));
    }

    /**
     * Display the sales reports page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        // Get customers for filtering
        $customers = Customer::all();

        // Get sales summary
        $invoices = Invoice::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalInvoices = $invoices->count();
        $paidInvoices = $invoices->filter(function($invoice) {
            return $invoice->payment_status === 'paid';
        })->count();
        $pendingInvoices = $invoices->filter(function($invoice) {
            return $invoice->payment_status === 'not paid';
        })->count();

        $invoices = Invoice::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalSales = $invoices->sum('total_amount');
        $totalCollected = InvoicePayment::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount_paid');

        // Get top customers
        $topCustomers = Invoice::with('shippment.customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function($invoice) {
                return $invoice->shippment->customer_id;
            })
            ->map(function($group) {
                return (object)[
                    'customer' => $group->first()->shippment->customer,
                    'customer_id' => $group->first()->shippment->customer_id,
                    'invoice_count' => $group->count(),
                    'total_amount' => $group->sum('total_amount')
                ];
            })
            ->sortByDesc('total_amount')
            ->take(5)
            ->values();

        return view('reports.sales', compact(
            'startDate',
            'endDate',
            'customers',
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'totalSales',
            'totalCollected',
            'topCustomers'
        ));
    }

    /**
     * Display the expense reports page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function expenses(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();

        // Get expense categories for filtering
        $expenseCategories = ExpenseCategory::all();

        // Get expense summary
        $totalExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('estimated_cost');

        // Get expenses by particular (using particular as a category)
        $expensesByCategory = Expense::select('particular', DB::raw('SUM(estimated_cost) as total_amount'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('particular')
            ->get();

        // Get recent expenses
        $recentExpenses = Expense::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('reports.expenses', compact(
            'startDate',
            'endDate',
            'expenseCategories',
            'totalExpenses',
            'expensesByCategory',
            'recentExpenses'
        ));
    }

    /**
     * Generate a report based on the provided parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $reportType = $request->input('report_type');
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        $filters = $request->except(['_token', 'report_type', 'start_date', 'end_date']);

        $data = [];

        switch ($reportType) {
            case 'financial':
                $data = $this->generateFinancialReport($startDate, $endDate, $filters);
                break;
            case 'sales':
                $data = $this->generateSalesReport($startDate, $endDate, $filters);
                break;
            case 'expenses':
                $data = $this->generateExpensesReport($startDate, $endDate, $filters);
                break;
            default:
                return back()->with('error', 'Invalid report type');
        }

        return view('reports.generated.' . $reportType, compact('data', 'startDate', 'endDate', 'filters'));
    }

    /**
     * Export a report to PDF or Excel.
     *
     * @param  string  $type
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export($type, Request $request)
    {
        $reportType = $request->input('report_type');
        $format = $request->input('format', 'pdf');
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        $filters = $request->except(['_token', 'report_type', 'start_date', 'end_date', 'format']);

        $data = [];

        switch ($reportType) {
            case 'financial':
                $data = $this->generateFinancialReport($startDate, $endDate, $filters);
                break;
            case 'sales':
                $data = $this->generateSalesReport($startDate, $endDate, $filters);
                break;
            case 'expenses':
                $data = $this->generateExpensesReport($startDate, $endDate, $filters);
                break;
            default:
                return back()->with('error', 'Invalid report type');
        }

        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        if ($format === 'pdf') {
            $pdf = \PDF::loadView('reports.exports.' . $reportType . '_pdf', $data);
            return $pdf->download($reportType . '_report_' . Carbon::now()->format('Y-m-d') . '.pdf');
        } else {
            return Excel::download(new ReportExport($data, $reportType), $reportType . '_report_' . Carbon::now()->format('Y-m-d') . '.xlsx');
        }
    }

    /**
     * Generate financial report data.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  array  $filters
     * @return array
     */
    private function generateFinancialReport($startDate, $endDate, $filters)
    {
        $query = Transaction::whereBetween('created_at', [$startDate, $endDate]);

        if (isset($filters['account_id']) && $filters['account_id']) {
            $query->where('account_id', $filters['account_id']);
        }

        if (isset($filters['transaction_type']) && $filters['transaction_type']) {
            $query->where('transaction_type', $filters['transaction_type']);
        }

        $transactions = $query->with(['account', 'related_account'])->get();

        $revenue = InvoicePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount_paid');
        $expenses = Expense::whereBetween('created_at', [$startDate, $endDate])->sum('estimated_cost');

        return [
            'transactions' => $transactions,
            'totalRevenue' => $revenue,
            'totalExpenses' => $expenses,
            'netProfit' => $revenue - $expenses,
            'accountBalances' => PaymentAccount::all()
        ];
    }

    /**
     * Generate sales report data.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  array  $filters
     * @return array
     */
    private function generateSalesReport($startDate, $endDate, $filters)
    {
        $query = Invoice::whereBetween('created_at', [$startDate, $endDate]);

        if (isset($filters['customer_id']) && $filters['customer_id']) {
            $query->whereHas('shippment', function($q) use ($filters) {
                $q->where('customer_id', $filters['customer_id']);
            });
        }

        // Filter by payment status using the collection instead of a query
        $invoices = $query->with(['shippment.customer', 'payments'])->get();

        if (isset($filters['payment_status']) && $filters['payment_status']) {
            $invoices = $invoices->filter(function($invoice) use ($filters) {
                return $invoice->payment_status === $filters['payment_status'];
            });
        }

        // Invoices are already loaded above

        $totalSales = $invoices->sum('total_amount');
        $totalCollected = InvoicePayment::whereBetween('created_at', [$startDate, $endDate])->sum('amount_paid');

        return [
            'invoices' => $invoices,
            'totalSales' => $totalSales,
            'totalCollected' => $totalCollected,
            'outstandingBalance' => $totalSales - $totalCollected,
            'customers' => Customer::all()
        ];
    }

    /**
     * Generate expenses report data.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  array  $filters
     * @return array
     */
    private function generateExpensesReport($startDate, $endDate, $filters)
    {
        $query = Expense::whereBetween('created_at', [$startDate, $endDate]);

        if (isset($filters['particular']) && $filters['particular']) {
            $query->where('particular', $filters['particular']);
        }

        $expenses = $query->get();

        $expensesByCategory = $expenses->groupBy('particular')
            ->map(function($group) {
                return [
                    'category_name' => $group->first()->particular,
                    'total' => $group->sum('estimated_cost'),
                    'count' => $group->count()
                ];
            })
            ->values();

        return [
            'expenses' => $expenses,
            'totalExpenses' => $expenses->sum('estimated_cost'),
            'expensesByCategory' => $expensesByCategory,
            'categories' => Expense::select('particular')->distinct()->get()
        ];
    }
}
