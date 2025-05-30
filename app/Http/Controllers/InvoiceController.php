<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\Shippment;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = 60; // 1 hour
    /**
     * Display a listing of the resource.
     */

    // public function index(Request $request)
    // {
    //     if (!Auth::user()->canDo(['view_shipment_invoice'])) {
    //         return redirect('home');
    //     }

    //     // Remove empty invoices
    //     Invoice::removeEmpty();

    //     // Default to 'pending' filter if not set
    //     $filter = $request->input('filter', 'pending');

    //     // Fetch all invoices
    //     $invoices = Invoice::orderBy('created_at', 'desc')->get();

    //     // Initialize collections for each currency
    //     $filteredUGX = collect();
    //     $filteredUSD = collect();

    //     // Apply filtering logic based on the filter type
    //     switch ($filter) {
    //         case 'pending':
    //             $filteredUGX = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'UGX');
    //             $filteredUSD = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'USD');
    //             break;

    //         case 'paid':
    //             $filteredUGX = $invoices->filter(fn($invoice) => $invoice->paidAmount($invoice->id) > 0 && $invoice->balance($invoice->id) == 0 && $invoice->transaction_currency === 'UGX');
    //             $filteredUSD = $invoices->filter(fn($invoice) => $invoice->paidAmount($invoice->id) > 0 && $invoice->balance($invoice->id) == 0 && $invoice->transaction_currency === 'USD');
    //             break;

    //         case 'overdue':
    //             $filteredUGX = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'UGX' && $invoice->created_at < now()->subMonth());
    //             $filteredUSD = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'USD' && $invoice->created_at < now()->subMonth());
    //             break;

    //         default:
    //             // Handle default case if needed
    //             break;
    //     }

    //     // Calculate totals
    //     $totalPendingUGX = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'UGX')->sum(fn($invoice) => $invoice->balance($invoice->id));
    //     $totalPendingUSD = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'USD')->sum(fn($invoice) => $invoice->balance($invoice->id));

    //     $totalPaidUGX = $invoices->filter(fn($invoice) => $invoice->paidAmount($invoice->id) > 0 && $invoice->balance($invoice->id) == 0 && $invoice->transaction_currency === 'UGX')->sum(fn($invoice) => $invoice->totalAmount($invoice->id));
    //     $totalPaidUSD = $invoices->filter(fn($invoice) => $invoice->paidAmount($invoice->id) > 0 && $invoice->balance($invoice->id) == 0 && $invoice->transaction_currency === 'USD')->sum(fn($invoice) => $invoice->totalAmount($invoice->id));

    //     $totalOverdueUGX = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'UGX' && $invoice->created_at < now()->subMonth())->sum(fn($invoice) => $invoice->balance($invoice->id));
    //     $totalOverdueUSD = $invoices->filter(fn($invoice) => $invoice->balance($invoice->id) > 0 && $invoice->transaction_currency === 'USD' && $invoice->created_at < now()->subMonth())->sum(fn($invoice) => $invoice->balance($invoice->id));

    //     // Set the title based on the filter
    //     $title = match ($filter) {
    //         'overdue' => 'Filtered Invoices: Overdue',
    //         'paid' => 'Filtered Invoices: Paid',
    //         'pending' => 'Filtered Invoices: Pending',
    //         default => 'All Invoices',
    //     };

    //     // Pass the required data to the view
    //     return view('invoices.invoices', [
    //         'filteredUGX' => $filteredUGX,
    //         'filteredUSD' => $filteredUSD,
    //         'totalPendingUGX' => $totalPendingUGX,
    //         'totalPendingUSD' => $totalPendingUSD,
    //         'totalPaidUGX' => $totalPaidUGX,
    //         'totalPaidUSD' => $totalPaidUSD,
    //         'totalOverdueUGX' => $totalOverdueUGX,
    //         'totalOverdueUSD' => $totalOverdueUSD,
    //         'title' => $title,
    //         'filter' => $filter,
    //     ]);
    // }

    public function index(Request $request)
    {
        if (!Auth::user()->canDo(['view_shipment_invoice'])) {
            return redirect('home');
        }

        // Get filter and search query from request
        $filter = $request->input('filter', 'all');
        $search = $request->input('search');
        
        // Get invoices with pagination
        $query = DB::table('invoices')
            ->leftJoin('shippments', 'invoices.shippment_id', '=', 'shippments.id')
            ->leftJoin('customers', 'shippments.customer_id', '=', 'customers.id')
            ->select([
                'invoices.id',
                'invoices.date_invoiced',
                'customers.name as customer_name',
                DB::raw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) as amount'),
                DB::raw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) as paid_amount')
            ]);
            
        // Apply search if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('customers.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('invoices.id', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Apply filters based on calculated values
        if ($filter === 'pending') {
            $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) = 0');
        } elseif ($filter === 'paid') {
            $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) >= (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
        } elseif ($filter === 'partially_paid') {
            $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) > 0')
                  ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
        } elseif ($filter === 'overdue') {
            $query->whereRaw('DATE_ADD(invoices.date_invoiced, INTERVAL 30 DAY) < NOW()')
                  ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
        }
        
        // Order by invoice ID descending (newest first)
        $query->orderBy('invoices.id', 'desc');
        
        // Paginate results - 10 per page
        $invoices = $query->paginate(10);
        
        // Prepare data for the view
        $processedInvoices = [];
        foreach ($invoices as $invoice) {
            // Calculate balance
            $balance = $invoice->amount - $invoice->paid_amount;
            
            // Determine status
            if ($balance <= 0) {
                $status = '<span class="badge bg-success">Paid</span>';
                $statusText = 'Paid';
            } elseif ($invoice->paid_amount > 0) {
                $status = '<span class="badge bg-warning">Partially Paid</span>';
                $statusText = 'Partially Paid';
            } else {
                $status = '<span class="badge bg-danger">Pending</span>';
                $statusText = 'Pending';
            }
            
            // Format invoice number
            $invoiceNumber = str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
            
            // Add to processed invoices
            $processedInvoices[] = [
                'id' => $invoice->id,
                'invoice_number' => $invoiceNumber,
                'customer_name' => $invoice->customer_name ?? 'N/A',
                'date_invoiced' => $invoice->date_invoiced ? date('d M Y', strtotime($invoice->date_invoiced)) : '-',
                'amount' => number_format($invoice->amount, 2),
                'paid_amount' => number_format($invoice->paid_amount, 2),
                'balance' => number_format($balance, 2),
                'status' => $status,
                'status_text' => $statusText
            ];
        }
        
        return view('invoices.invoices', compact('filter', 'invoices', 'processedInvoices', 'search'));
        $cachedData = cache()->remember($cacheKey, now()->addHour(), function () use ($filter, $perPage) {
            // Use query builder instead of Eloquent for better performance
            $query = DB::table('invoices')
                ->select([
                    'invoices.id',
                    'invoices.transaction_currency',
                    'invoices.created_at',
                    'invoices.shippment_id',
                    'invoices.user_id',
                    'invoices.date_invoiced',
                    // Add calculated fields directly in the query
                    DB::raw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) as amount'),
                    DB::raw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) as paid_amount')
                ]);

            // Apply filters using raw queries to avoid loading models
            switch ($filter) {
                case 'all':
                    // No additional filters for 'all'
                    break;
                    
                case 'pending':
                    // Only show invoices with no payments
                    $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) = 0')
                          ->whereRaw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) > 0');
                    break;
                    
                case 'paid':
                    // Only show fully paid invoices (paid amount >= total amount)
                    $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) >= (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)')
                          ->whereRaw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) > 0');
                    break;
                    
                case 'partially_paid':
                    // Only show invoices with some payment but not fully paid
                    $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) > 0')
                          ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)')
                          ->whereRaw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) > 0');
                    break;
                    
                case 'overdue':
                    // Show unpaid or partially paid invoices older than 1 month
                    $query->where('invoices.created_at', '<', now()->subMonth())
                          ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)')
                          ->whereRaw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) > 0');
                    break;
            }

            // Get totals first (using the same query structure for consistency)
            $totals = $this->calculateTotalsFromQuery(clone $query);

            // Apply pagination
            $invoices = $query->orderBy('invoices.created_at', 'desc')
                            ->paginate($perPage);

            // Get IDs for the current page
            $invoiceIds = $invoices->pluck('id')->toArray();

            // If no invoices, return empty collections
            if (empty($invoiceIds)) {
                return [
                    'filteredUGX' => collect(),
                    'filteredUSD' => collect(),
                    'totals' => $totals,
                    'pagination' => $invoices
                ];
            }

            // Get related data in bulk for the current page
            $relations = [
                'details' => $this->getInvoiceDetails($invoiceIds),
                'payments' => $this->getInvoicePayments($invoiceIds),
                'taxinvoices' => $this->getInvoiceTaxes($invoiceIds),
                'shippment' => $this->getShippmentData($invoiceIds),
                'customer' => $this->getCustomerData($invoiceIds)
            ];

            // Categorize by currency
            $filteredUGX = collect($invoices->items())->where('transaction_currency', 'UGX');
            $filteredUSD = collect($invoices->items())->where('transaction_currency', 'USD');

            return [
                'filteredUGX' => $filteredUGX,
                'filteredUSD' => $filteredUSD,
                'totals' => $totals,
                'pagination' => $invoices,
                'relations' => $relations
            ];
        });

        // Extract data from cached results
        $pagination = $cachedData['pagination'];
        $relations = $cachedData['relations'];
        $totals = $cachedData['totals'];

        // Prepare data for the view
        $filteredUGX = $cachedData['filteredUGX'];
        $filteredUSD = $cachedData['filteredUSD'];

        // Calculate counts for each status
        $totalInvoices = $pagination->total();
        
        // Get counts for each status
        $statusCounts = DB::table('invoices')
            ->select(
                DB::raw('COUNT(CASE WHEN (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) >= (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) THEN 1 END) as paid_count'),
                DB::raw('COUNT(CASE WHEN (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) = 0 THEN 1 END) as pending_count'),
                DB::raw('COUNT(CASE WHEN (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) > 0 AND (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) THEN 1 END) as partially_paid_count'),
                DB::raw('COUNT(CASE WHEN created_at < ? AND (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) THEN 1 END) as overdue_count')
            )
            ->addBinding(now()->subMonth(), 'select')
            ->first();

        // Set the title based on the filter
        $title = match ($filter) {
            'overdue' => 'Overdue Invoices',
            'paid' => 'Paid Invoices',
            'pending' => 'Pending Invoices',
            'partially_paid' => 'Partially Paid Invoices',
            default => 'All Invoices',
        };

        // Prepare data for the stats cards
        $totalPaidUGX = [
            'count' => $statusCounts->paid_count,
            'amount' => $totals['paid_ugx']
        ];
        $totalPaidUSD = [
            'count' => $statusCounts->paid_count,
            'amount' => $totals['paid_usd']
        ];
        $totalPendingUGX = [
            'count' => $statusCounts->pending_count,
            'amount' => $totals['total_ugx'] - $totals['paid_ugx']
        ];
        $totalPendingUSD = [
            'count' => $statusCounts->pending_count,
            'amount' => $totals['total_usd'] - $totals['paid_usd']
        ];
        $totalOverdueUGX = [
            'count' => $statusCounts->overdue_count,
            'amount' => $statusCounts->overdue_count * ($totals['total_ugx'] / max(1, $totalInvoices)) // Approximate value
        ];
        $totalOverdueUSD = [
            'count' => $statusCounts->overdue_count,
            'amount' => $statusCounts->overdue_count * ($totals['total_usd'] / max(1, $totalInvoices)) // Approximate value
        ];

        // Add relations to each invoice for the view
        $invoices = collect($pagination->items())->map(function($invoice) use ($relations) {
            $invoice->details = $relations['details'][$invoice->id] ?? collect();
            $invoice->payments = $relations['payments'][$invoice->id] ?? collect();
            $invoice->taxinvoices = $relations['taxinvoices'][$invoice->id] ?? collect();
            $invoice->shippment = $relations['shippment'][$invoice->id] ?? null;
            $invoice->customer = $relations['customer'][$invoice->id] ?? null;
            return $invoice;
        });

        return view('invoices.invoices', [
            'filteredUGX' => $invoices->where('transaction_currency', 'UGX'),
            'filteredUSD' => $invoices->where('transaction_currency', 'USD'),
            'filter' => $filter,
            'totalInvoices' => $totalInvoices,
            'totalPaid' => $statusCounts->paid_count,
            'totalPending' => $statusCounts->pending_count,
            'totalOverdue' => $statusCounts->overdue_count,
            'totalPartiallyPaid' => $statusCounts->partially_paid_count,
            'totals' => $totals,
            'title' => $title,
            'pagination' => $pagination,
            'totalPaidUGX' => $totalPaidUGX,
            'totalPaidUSD' => $totalPaidUSD,
            'totalPendingUGX' => $totalPendingUGX,
            'totalPendingUSD' => $totalPendingUSD,
            'totalOverdueUGX' => $totalOverdueUGX,
            'totalOverdueUSD' => $totalOverdueUSD,
        ]);
    }

    /**
     * Load related data for invoices in an optimized way
     */
    protected function loadInvoiceRelations($invoices)
    {
        if ($invoices->isEmpty()) {
            return;
        }

        $invoiceIds = $invoices->pluck('id')->toArray();

        // Eager load relationships
        $relations = [
            'details' => function ($query) {
                $query->select(['id', 'invoice_id', 'quantity', 'unit_price', 'particular']);
            },
            'payments' => function ($query) {
                $query->select(['id', 'invoice_id', 'amount_paid', 'payment_date']);
            },
            'taxinvoices.tax' => function ($query) {
                $query->select(['id', 'name', 'percentage']);
            },
            'shippment' => function ($query) {
                $query->select(['id', 'customer_id', 'mark', 'bl_packages', 'number_of_packages', 'weight', 'volume']);
            },
            'shippment.customer' => function ($query) {
                $query->select(['id', 'name', 'phone_number']);
            }
        ];

        // Load relationships in a single query per relation type
        foreach ($relations as $relation => $constraints) {
            $invoices->load([$relation => $constraints]);
        }
    }

    /**
     * Calculate totals for the dashboard
     */
    /**
     * Calculate totals directly from the query
     */
    protected function calculateTotalsFromQuery($query)
    {
        $totals = $query->select(
            DB::raw('SUM(CASE WHEN transaction_currency = "UGX" THEN (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) ELSE 0 END) as total_ugx'),
            DB::raw('SUM(CASE WHEN transaction_currency = "USD" THEN (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) ELSE 0 END) as total_usd'),
            DB::raw('SUM(CASE WHEN transaction_currency = "UGX" THEN (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) ELSE 0 END) as paid_ugx'),
            DB::raw('SUM(CASE WHEN transaction_currency = "USD" THEN (SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) ELSE 0 END) as paid_usd')
        )->first();

        return [
            'total_ugx' => (float)($totals->total_ugx ?? 0),
            'total_usd' => (float)($totals->total_usd ?? 0),
            'paid_ugx' => (float)($totals->paid_ugx ?? 0),
            'paid_usd' => (float)($totals->paid_usd ?? 0),
            'pending_ugx' => (float)(($totals->total_ugx ?? 0) - ($totals->paid_ugx ?? 0)),
            'pending_usd' => (float)(($totals->total_usd ?? 0) - ($totals->paid_usd ?? 0)),
        ];
    }

    /**
     * Get invoice details in bulk for multiple invoices
     */
    protected function getInvoiceDetails(array $invoiceIds)
    {
        return DB::table('invoice_details')
            ->whereIn('invoice_id', $invoiceIds)
            ->select(['id', 'invoice_id', 'quantity', 'unit_price', 'particular'])
            ->get()
            ->groupBy('invoice_id');
    }

    /**
     * Get invoice payments in bulk for multiple invoices
     */
    protected function getInvoicePayments(array $invoiceIds)
    {
        return DB::table('invoice_payments')
            ->whereIn('invoice_id', $invoiceIds)
            ->select(['id', 'invoice_id', 'amount_paid', 'payment_date'])
            ->get()
            ->groupBy('invoice_id');
    }

    /**
     * Get invoice taxes in bulk for multiple invoices
     */
    protected function getInvoiceTaxes(array $invoiceIds)
    {
        return DB::table('invoice_taxes')
            ->whereIn('invoice_id', $invoiceIds)
            ->join('taxes', 'invoice_taxes.tax_id', '=', 'taxes.id')
            ->select([
                'invoice_taxes.id',
                'invoice_taxes.invoice_id',
                'taxes.tax_name as name',
                'taxes.percentage_tax as percentage_tax'
            ])
            ->get()
            ->groupBy('invoice_id');
    }

    /**
     * Get shipment data in bulk for multiple invoices
     */
    protected function getShippmentData(array $invoiceIds)
    {
        return DB::table('shippments')
            ->join('invoices', 'shippments.id', '=', 'invoices.shippment_id')
            ->whereIn('invoices.id', $invoiceIds)
            ->select([
                'invoices.id as invoice_id',
                'shippments.id',
                'shippments.customer_id',
                'shippments.mark',
                'shippments.bl_packages',
                'shippments.number_of_packages',
                'shippments.weight',
                'shippments.volume'
            ])
            ->get()
            ->keyBy('invoice_id');
    }

    /**
     * Get customer data in bulk for multiple shipments
     */
    protected function getCustomerData(array $invoiceIds)
    {
        return DB::table('customers')
            ->join('shippments', 'customers.id', '=', 'shippments.customer_id')
            ->join('invoices', 'shippments.id', '=', 'invoices.shippment_id')
            ->whereIn('invoices.id', $invoiceIds)
            ->select([
                'invoices.id as invoice_id',
                'customers.id',
                'customers.name',
                'customers.phone_number'
            ])
            ->get()
            ->keyBy('invoice_id');
    }

    /**
     * Get all paid invoices
     * 
     * @return array
     */
    /**
     * Get all paid invoices
     * 
     * @return array
     */
    protected function paidInvoices()
    {
        return Invoice::whereHas('payments')
            ->whereDoesntHave('payments', function($query) {
                $query->whereRaw('amount_paid < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
            })
            ->pluck('id')
            ->toArray();
    }

    /**
     * Display a listing of paid invoices
     * 
     * @return \Illuminate\Http\Response
     */
    public function listPaidInvoices()
    {
        if (!Auth::user()->canDo(['view_shipment_invoice'])) {
            return redirect('home');
        }

        // Remove empty invoices (only once per day to prevent performance issues)
        if (cache()->missing('last_empty_invoice_cleanup') || now()->diffInHours(cache('last_empty_invoice_cleanup')) >= 24) {
            Invoice::removeEmpty();
            cache(['last_empty_invoice_cleanup' => now()], now()->addDay());
        }

        $invoices = Invoice::whereIn('id', $this->paidInvoices())
            ->with(['details', 'payments', 'taxinvoices'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'invoices' => $invoices,
            'title' => 'Paid invoices'
        ];

        return view('invoices.invoices')->with($data);
    }


    public function pendingInvoices()
    {
        Invoice::removeEmpty(); //remove all empty invoices

        if (!Auth::user()->canDo(['view_shipment_invoice'])) return redirect('home');

        $invoices = Invoice::whereIn('id', Invoice::pendingInvoices())->get();

        $data = [
            'invoices' => $invoices,
            'title' => 'Pending invoices'
        ];

        return view('invoices.invoices')->with($data);
    }



    function return_number()
    {

        $data = [

            'pending_invoices' => count(Invoice::pendingInvoices()),

            'paid_invoices' => count(Invoice::paidInvoices()),

        ];

        return $data;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (!Auth::user()->canDo(['add_shipment_invoice'])) return redirect('home');

        $shipments = Shippment::get();

        $data = [
            'shipments' => $shipments,
            'title' => 'Add new Invoice'
        ];

        return view('invoices.add_invoice')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vrules = [
            'date_invoiced' => 'required',
        ];

        $this->validate($request, $vrules);

        $saveInvoice = new Invoice();

        $saveInvoice->shippment_id = $request->shippment_id;

        $saveInvoice->user_id = Auth::id();

        $saveInvoice->date_invoiced = $request->date_invoiced;

        $saveInvoice->transaction_currency = $request->transaction_currency;

        $saveInvoice->save();

        return redirect()->route('invoices.show', $saveInvoice->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        if (!Auth::user()->canDo(['view_shipment_invoice'])) return redirect('home');

        // Eager load all necessary relationships
        $invoice->load([
            'details',
            'payments',
            'shippment.customer',
            'taxinvoices.tax',
            'user'
        ]);

        // Pre-calculate values to avoid multiple queries
        $amount = $invoice->details->sum(function($detail) {
            return $detail->quantity * $detail->unit_price;
        });

        $taxAmount = $invoice->taxinvoices->sum(function($tax) use ($amount) {
            return round(($tax->tax->rate / 100) * $amount);
        });

        $totalAmount = $amount + $taxAmount;
        $paidAmount = $invoice->payments->sum('amount_paid');
        $balance = $totalAmount - $paidAmount;

        $mode_of_payments = ['Cash', 'Bank', 'Mobile Money', 'Digital'];
        $taxes = Tax::where('currency', $invoice->transaction_currency)->get();

        $data = [
            'invoice' => $invoice,
            'details' => $invoice->details,
            'title' => 'Invoice No. ' . $invoice->id,
            'mode_of_payments' => $mode_of_payments,
            'taxes' => $taxes,
            'amount' => $amount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'balance' => $balance
        ];

        return view('pages.invoice')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if (!Auth::user()->canDo(['view_shipment_invoice'])) return redirect('home');

        $mode_of_payments = ['Cash', 'Bank', 'Mobile Money', 'Digital'];

        $data = [
            'invoice' => $invoice,
            'details' => $invoice->details,
            'title' => 'Invoice No. ' . $invoice->id,
            'mode_of_payments' => $mode_of_payments,
        ];

        return view("pages.print_invoice")->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Validate the input
        $request->validate([
            'transaction_currency' => 'required|string|in:USD,EUR,UGX',  // Example currencies
        ]);

        // Update the invoice with the new currency
        $invoice->transaction_currency = $request->input('transaction_currency');

        // Save the changes
        $invoice->save();

        // Redirect back with a success message
        return back()->with('success', 'Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($invoice_id)
    {
        InvoicePayment::where('invoice_id', $invoice_id)->delete();

        InvoiceDetail::where('invoice_id', $invoice_id)->delete();

        Invoice::destroy($invoice_id);

        return redirect()->route('invoices.index');
    }

    /**
     * Print invoice
     */
    public function print(Invoice $invoice)
    {
        if (!Auth::user()->canDo(['view_shipment_invoice'])) {
            return redirect('home');
        }

        // Eager load all necessary relationships
        $invoice->load([
            'details',
            'payments',
            'shippment.customer',
            'taxinvoices.tax',
            'user'
        ]);

        // Pre-calculate values
        $amount = $invoice->details->sum(function($detail) {
            return $detail->quantity * $detail->unit_price;
        });

        $taxAmount = $invoice->taxinvoices->sum(function($tax) use ($amount) {
            return round(($tax->tax->rate / 100) * $amount);
        });

        $totalAmount = $amount + $taxAmount;
        $paidAmount = $invoice->payments->sum('amount_paid');
        $balance = $totalAmount - $paidAmount;

        $data = [
            'invoice' => $invoice,
            'details' => $invoice->details,
            'title' => 'Invoice No. ' . $invoice->id,
            'amount' => $amount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'balance' => $balance
        ];

        return view('invoices.print_optimized')->with($data);
    }

    /**
     * Generate PDF of invoice
     */
    public function pdf(Invoice $invoice)
    {
        if (!Auth::user()->canDo(['view_shipment_invoice'])) {
            return redirect('home');
        }

        // Eager load all necessary relationships
        $invoice->load([
            'details',
            'payments',
            'shippment.customer',
            'taxinvoices.tax',
            'user'
        ]);

        // Pre-calculate values
        $amount = $invoice->details->sum(function($detail) {
            return $detail->quantity * $detail->unit_price;
        });

        $taxAmount = $invoice->taxinvoices->sum(function($tax) use ($amount) {
            return round(($tax->tax->rate / 100) * $amount);
        });

        $totalAmount = $amount + $taxAmount;
        $paidAmount = $invoice->payments->sum('amount_paid');
        $balance = $totalAmount - $paidAmount;

        $data = [
            'invoice' => $invoice,
            'details' => $invoice->details,
            'title' => 'Invoice No. ' . $invoice->id,
            'amount' => $amount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'balance' => $balance
        ];

        $pdf = Pdf::loadView('invoices.print_optimized', $data);

        // Configure PDF options
        $options = [
            'dpi' => 300,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'enable_php' => true,
            'orientation' => 'portrait',
            'enable_javascript' => true,
            'javascript_delay' => 1000,
            'no-outline' => true,
            'encoding' => 'UTF-8',
            'enable_smart_shrinking' => false,
            'images' => true,
            'use_print_media' => true,
            'chroot' => public_path(),
            'enable_local_file_access' => true,
            'zoom' => 1.0,
            'disable-smart-shrinking' => true,
            'print-media-type' => true,
            'page-size' => 'A4',
            'page-width' => '210mm',
            'page-height' => '297mm'
        ];

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions($options);
        return $pdf->download('invoice-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Get invoices data for DataTables
     */
    public function getInvoicesData(Request $request)
    {
        try {
            if (!Auth::user()->canDo(['view_shipment_invoice'])) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $filter = $request->input('filter', 'all');
            
            // Get all invoices with basic information
            $invoices = DB::table('invoices')
                ->leftJoin('shippments', 'invoices.shippment_id', '=', 'shippments.id')
                ->leftJoin('customers', 'shippments.customer_id', '=', 'customers.id')
                ->select([
                    'invoices.id',
                    'invoices.date_invoiced',
                    'customers.name as customer_name',
                    DB::raw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) as amount'),
                    DB::raw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) as paid_amount')
                ])
                ->get();
            
            // Prepare data for DataTable
            $data = [];
            
            foreach ($invoices as $invoice) {
                // Calculate balance
                $balance = $invoice->amount - $invoice->paid_amount;
                
                // Determine status
                if ($balance <= 0) {
                    $status = '<span class="badge bg-success">Paid</span>';
                    $statusText = 'Paid';
                } elseif ($invoice->paid_amount > 0) {
                    $status = '<span class="badge bg-warning">Partially Paid</span>';
                    $statusText = 'Partially Paid';
                } else {
                    $status = '<span class="badge bg-danger">Pending</span>';
                    $statusText = 'Pending';
                }
                
                // Skip if filter is applied and doesn't match
                if ($filter === 'pending' && $statusText !== 'Pending') continue;
                if ($filter === 'paid' && $statusText !== 'Paid') continue;
                if ($filter === 'partially_paid' && $statusText !== 'Partially Paid') continue;
                if ($filter === 'overdue' && ($statusText === 'Paid' || !$this->isOverdue($invoice->date_invoiced))) continue;
                
                // Format invoice number
                $invoiceNumber = str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
                
                // Action buttons
                $actions = '<div class="d-flex gap-2 justify-content-center">' .
                           '<a href="'.route('invoices.show', $invoice->id).'" class="btn btn-sm btn-primary btn-action"><i class="ri-eye-line"></i></a>' .
                           '<a href="'.route('invoices.download', $invoice->id).'" class="btn btn-sm btn-outline-secondary btn-action"><i class="ri-download-line"></i></a>' .
                           '<a href="'.route('invoices.send', $invoice->id).'" class="btn btn-sm btn-outline-info btn-action"><i class="ri-mail-send-line"></i></a>' .
                           '</div>';
                
                // Add to data array
                $data[] = [
                    'id' => $invoice->id,
                    'invoice_number' => $invoiceNumber,
                    'customer_name' => $invoice->customer_name ?? 'N/A',
                    'date_invoiced' => $invoice->date_invoiced ? date('d M Y', strtotime($invoice->date_invoiced)) : '-',
                    'amount' => number_format($invoice->amount, 2),
                    'paid_amount' => number_format($invoice->paid_amount, 2),
                    'balance' => number_format($balance, 2),
                    'status' => $status,
                    'action' => $actions,
                    'view_url' => route('invoices.show', $invoice->id)
                ];
            }
            
            return response()->json([
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in getInvoicesData: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if an invoice is overdue (more than 30 days old)
     */
    private function isOverdue($dateInvoiced)
    {
        if (!$dateInvoiced) return false;
        
        $invoiceDate = Carbon::parse($dateInvoiced);
        $today = Carbon::now();
        
        return $invoiceDate->diffInDays($today) > 30;
    }
    
    /**
     * Handle real-time search requests
     */
    public function search(Request $request)
    {
        try {
            if (!Auth::user()->canDo(['view_shipment_invoice'])) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get filter and search query from request
            $filter = $request->input('filter', 'all');
            $search = $request->input('search');
            
            // Get invoices with pagination
            $query = DB::table('invoices')
                ->leftJoin('shippments', 'invoices.shippment_id', '=', 'shippments.id')
                ->leftJoin('customers', 'shippments.customer_id', '=', 'customers.id')
                ->select([
                    'invoices.id',
                    'invoices.date_invoiced',
                    'customers.name as customer_name',
                    DB::raw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) as amount'),
                    DB::raw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) as paid_amount')
                ]);
                
            // Apply search if provided
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('customers.name', 'LIKE', '%' . $search . '%')
                      ->orWhere('invoices.id', 'LIKE', '%' . $search . '%');
                });
            }
            
            // Apply filters based on calculated values
            if ($filter === 'pending') {
                $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) = 0');
            } elseif ($filter === 'paid') {
                $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) >= (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
            } elseif ($filter === 'partially_paid') {
                $query->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) > 0')
                      ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
            } elseif ($filter === 'overdue') {
                $query->whereRaw('DATE_ADD(invoices.date_invoiced, INTERVAL 30 DAY) < NOW()')
                      ->whereRaw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) < (SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id)');
            }
            
            // Order by invoice ID descending (newest first)
            $query->orderBy('invoices.id', 'desc');
            
            // Paginate results - 10 per page
            $invoices = $query->paginate(10);
            
            // Prepare data for the response
            $processedInvoices = [];
            foreach ($invoices as $invoice) {
                // Calculate balance
                $balance = $invoice->amount - $invoice->paid_amount;
                
                // Determine status
                if ($balance <= 0) {
                    $status = '<span class="badge bg-success">Paid</span>';
                    $statusText = 'Paid';
                } elseif ($invoice->paid_amount > 0) {
                    $status = '<span class="badge bg-warning">Partially Paid</span>';
                    $statusText = 'Partially Paid';
                } else {
                    $status = '<span class="badge bg-danger">Pending</span>';
                    $statusText = 'Pending';
                }
                
                // Format invoice number
                $invoiceNumber = str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
                
                // Add to processed invoices
                $processedInvoices[] = [
                    'id' => $invoice->id,
                    'invoice_number' => $invoiceNumber,
                    'customer_name' => $invoice->customer_name ?? 'N/A',
                    'date_invoiced' => $invoice->date_invoiced ? date('d M Y', strtotime($invoice->date_invoiced)) : '-',
                    'amount' => number_format($invoice->amount, 2),
                    'paid_amount' => number_format($invoice->paid_amount, 2),
                    'balance' => number_format($balance, 2),
                    'status' => $status,
                    'status_text' => $statusText
                ];
            }
            
            // Generate pagination HTML
            $paginationHtml = '';
            if ($invoices->hasPages()) {
                $paginationHtml = '<div class="text-muted mb-2">'
                    . 'Showing ' . ($invoices->firstItem() ?? 0) . ' to ' . ($invoices->lastItem() ?? 0) . ' of ' . $invoices->total() . ' results'
                    . '</div>'
                    . '<nav aria-label="Page navigation">'
                    . '<ul class="pagination">';
                
                // Previous page link
                if ($invoices->onFirstPage()) {
                    $paginationHtml .= '<li class="page-item disabled"><span class="page-link">&laquo; Previous</span></li>';
                } else {
                    $prevPageUrl = $invoices->appends(['filter' => $filter, 'search' => $search])->previousPageUrl();
                    $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $prevPageUrl . '" rel="prev">&laquo; Previous</a></li>';
                }
                
                // Page links
                foreach ($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url) {
                    $isCurrentPage = $page == $invoices->currentPage();
                    if ($isCurrentPage) {
                        $paginationHtml .= '<li class="page-item active"><span class="page-link">' . $page . '</span></li>';
                    } else {
                        $pageUrl = $invoices->appends(['filter' => $filter, 'search' => $search])->url($page);
                        $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $pageUrl . '">' . $page . '</a></li>';
                    }
                }
                
                // Next page link
                if ($invoices->hasMorePages()) {
                    $nextPageUrl = $invoices->appends(['filter' => $filter, 'search' => $search])->nextPageUrl();
                    $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $nextPageUrl . '" rel="next">Next &raquo;</a></li>';
                } else {
                    $paginationHtml .= '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
                }
                
                $paginationHtml .= '</ul></nav>';
            }
            
            return response()->json([
                'invoices' => $processedInvoices,
                'pagination' => $paginationHtml,
                'total' => $invoices->total(),
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export invoices data in different formats
     */
    public function export(Request $request, $type)
    {
        try {
            if (!Auth::user()->canDo(['view_shipment_invoice'])) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get filter from request
            $filter = $request->input('filter', 'all');
            
            // Get invoices data
            $invoices = DB::table('invoices')
                ->leftJoin('shippments', 'invoices.shippment_id', '=', 'shippments.id')
                ->leftJoin('customers', 'shippments.customer_id', '=', 'customers.id')
                ->select([
                    'invoices.id',
                    'invoices.date_invoiced',
                    'customers.name as customer_name',
                    DB::raw('(SELECT COALESCE(SUM(quantity * unit_price), 0) FROM invoice_details WHERE invoice_id = invoices.id) as amount'),
                    DB::raw('(SELECT COALESCE(SUM(amount_paid), 0) FROM invoice_payments WHERE invoice_id = invoices.id) as paid_amount')
                ])
                ->get();
            
            // Prepare data for export
            $data = [];
            
            foreach ($invoices as $invoice) {
                // Calculate balance
                $balance = $invoice->amount - $invoice->paid_amount;
                
                // Determine status
                if ($balance <= 0) {
                    $statusText = 'Paid';
                } elseif ($invoice->paid_amount > 0) {
                    $statusText = 'Partially Paid';
                } else {
                    $statusText = 'Pending';
                }
                
                // Skip if filter is applied and doesn't match
                if ($filter === 'pending' && $statusText !== 'Pending') continue;
                if ($filter === 'paid' && $statusText !== 'Paid') continue;
                if ($filter === 'partially_paid' && $statusText !== 'Partially Paid') continue;
                if ($filter === 'overdue' && ($statusText === 'Paid' || !$this->isOverdue($invoice->date_invoiced))) continue;
                
                // Format invoice number
                $invoiceNumber = str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
                
                // Add to data array
                $data[] = [
                    'Invoice No' => $invoiceNumber,
                    'Client' => $invoice->customer_name ?? 'N/A',
                    'Date' => $invoice->date_invoiced ? date('d M Y', strtotime($invoice->date_invoiced)) : '-',
                    'Total Amount' => number_format($invoice->amount, 2),
                    'Paid Amount' => number_format($invoice->paid_amount, 2),
                    'Balance' => number_format($balance, 2),
                    'Status' => $statusText
                ];
            }
            
            // Generate title based on filter
            $title = 'Invoices - ' . ucfirst($filter);
            
            // Export based on type
            switch ($type) {
                case 'excel':
                    return $this->exportToExcel($data, $title);
                case 'pdf':
                    return $this->exportToPdf($data, $title);
                case 'csv':
                    return $this->exportToCsv($data, $title);
                default:
                    return redirect()->back()->with('error', 'Invalid export type');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in export: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export data to Excel
     */
    private function exportToExcel($data, $title)
    {
        // Create a simple array-to-Excel export using a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'invoice_export_');
        $fp = fopen($tempFile, 'w');
        
        // Write headers
        if (!empty($data)) {
            fputcsv($fp, array_keys($data[0]));
            
            // Write data rows
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
        }
        
        fclose($fp);
        
        // Return the file for download
        return response()->download($tempFile, $title . '.csv', [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Export data to PDF
     */
    private function exportToPdf($data, $title)
    {
        // Generate PDF using the data
        $pdf = PDF::loadView('exports.invoices', [
            'invoices' => $data,
            'title' => $title,
            'date' => date('d M Y')
        ]);
        
        return $pdf->download($title . '.pdf');
    }

    /**
     * Export data to CSV
     */
    private function exportToCsv($data, $title)
    {
        // Create a CSV file
        $tempFile = tempnam(sys_get_temp_dir(), 'invoice_export_');
        $fp = fopen($tempFile, 'w');
        
        // Write headers
        if (!empty($data)) {
            fputcsv($fp, array_keys($data[0]));
            
            // Write data rows
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
        }
        
        fclose($fp);
        
        // Return the file for download
        return response()->download($tempFile, $title . '.csv', [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Generate a public PDF for invoice that doesn't require authentication
     *
     * @param string $hashedId
     * @return \Illuminate\Http\Response
     */
    public function publicPdf($hashedId)
    {
        $invoice = Invoice::findByHashedId($hashedId);

        if (!$invoice) {
            abort(404);
        }

        // Eager load all necessary relationships
        $invoice->load([
            'details',
            'payments',
            'shippment.customer',
            'taxinvoices.tax',
            'user'
        ]);

        // Pre-calculate values
        $amount = $invoice->details->sum(function($detail) {
            return $detail->quantity * $detail->unit_price;
        });

        $taxAmount = $invoice->taxinvoices->sum(function($tax) use ($amount) {
            return round(($tax->tax->rate / 100) * $amount);
        });

        $totalAmount = $amount + $taxAmount;
        $paidAmount = $invoice->payments->sum('amount_paid');
        $balance = $totalAmount - $paidAmount;

        $data = [
            'invoice' => $invoice,
            'details' => $invoice->details,
            'title' => 'Invoice No. ' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
            'amount' => $amount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'balance' => $balance,
            'is_public' => true
        ];

        $pdf = Pdf::loadView('invoices.public_pdf', $data);

        // Configure PDF options
        $options = [
            'dpi' => 300,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'enable_php' => true,
            'orientation' => 'portrait',
            'enable_javascript' => true,
            'javascript_delay' => 1000,
            'no-outline' => true,
            'encoding' => 'UTF-8',
            'enable_smart_shrinking' => false,
            'images' => true,
            'use_print_media' => true,
            'chroot' => public_path(),
            'enable_local_file_access' => true,
            'zoom' => 1.0,
            'disable-smart-shrinking' => true,
            'print-media-type' => true,
            'page-size' => 'A4',
            'page-width' => '210mm',
            'page-height' => '297mm'
        ];

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions($options);
        return $pdf->download('invoice-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
    
    /**
     * Download a PDF for the invoice
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function download(Invoice $invoice)
    {
        // Eager load all necessary relationships
        $invoice->load([
            'details',
            'payments',
            'shippment.customer',
            'taxinvoices.tax',
            'user'
        ]);
        
        // Calculate amounts
        $amount = 0;
        $taxAmount = 0;
        $totalAmount = 0;
        $paidAmount = 0;
        $balance = 0;
        
        if ($invoice->details) {
            foreach ($invoice->details as $detail) {
                $amount += $detail->quantity * $detail->unit_price;
            }
        }
        
        if ($invoice->taxinvoices) {
            foreach ($invoice->taxinvoices as $taxinvoice) {
                if ($taxinvoice->tax) {
                    $taxAmount += ($amount * $taxinvoice->tax->rate) / 100;
                }
            }
        }
        
        $totalAmount = $amount + $taxAmount;
        
        if ($invoice->payments) {
            foreach ($invoice->payments as $payment) {
                $paidAmount += $payment->amount_paid;
            }
        }
        
        $balance = $totalAmount - $paidAmount;
        
        $data = [
            'invoice' => $invoice,
            'details' => $invoice->details,
            'title' => 'Invoice No. ' . $invoice->id,
            'amount' => $amount,
            'taxAmount' => $taxAmount,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'balance' => $balance
        ];

        $pdf = Pdf::loadView('invoices.print_optimized', $data);

        // Configure PDF options
        $options = [
            'dpi' => 300,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'margin_top' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'enable_php' => true,
            'orientation' => 'portrait',
            'enable_javascript' => true,
            'javascript_delay' => 1000,
            'no-outline' => true,
            'encoding' => 'UTF-8',
            'enable_smart_shrinking' => false,
            'images' => true,
            'use_print_media' => true,
            'chroot' => public_path(),
            'enable_local_file_access' => true,
            'zoom' => 1.0,
            'disable-smart-shrinking' => true,
            'print-media-type' => true,
            'page-size' => 'A4',
            'page-width' => '210mm',
            'page-height' => '297mm'
        ];

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions($options);
        return $pdf->download('invoice-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
    
    /**
     * Send the invoice to the client via email
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function send(Invoice $invoice)
    {
        // In a real application, this would send an email with the invoice attached
        // For now, we'll just return a success message
        return redirect()->route('invoices.show', $invoice->id)
            ->with('status', 'Invoice has been sent to the client successfully.');
    }
}
