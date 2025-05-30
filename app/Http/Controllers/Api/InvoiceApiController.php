<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceTax;
use App\Models\Quotation;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceApiController extends Controller
{
    /**
     * Display a listing of invoices with optional filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Remove empty invoices
        Invoice::removeEmpty();

        // Get the payment status filter
        $paymentStatus = $request->input('payment_status', 'all');

        // Get currency filter (optional)
        $currency = $request->input('currency');

        // Base query with relationships
        $query = Invoice::with(['details', 'payments', 'taxinvoices.tax', 'user', 'shippment']);

        // Apply payment status filter
        if ($paymentStatus === 'paid') {
            $paidIds = Invoice::paidInvoices();
            $query->whereIn('id', $paidIds);
        } elseif ($paymentStatus === 'pending') {
            $pendingIds = Invoice::pendingInvoices();
            $query->whereIn('id', $pendingIds);
        }

        // Apply currency filter if provided
        if ($currency) {
            $query->where('transaction_currency', $currency);
        }

        // Get paginated results
        $invoices = $query->orderBy('created_at', 'desc')->paginate(15);

        // Transform the data to include calculated fields
        $transformedInvoices = $invoices->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'hashed_id' => $invoice->hashed_id,
                'date_invoiced' => $invoice->date_invoiced,
                'transaction_currency' => $invoice->transaction_currency,
                'shippment' => $invoice->shippment ? [
                    'id' => $invoice->shippment->id,
                    'number' => $invoice->shippment->number ?? null,
                ] : null,
                'user' => [
                    'id' => $invoice->user->id,
                    'name' => $invoice->user->name,
                ],
                'amount' => $invoice->amount,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'balance' => $invoice->balance,
                'payment_status' => $invoice->balance <= 0 ? 'paid' : 'pending',
                'created_at' => $invoice->created_at,
                'updated_at' => $invoice->updated_at,
            ];
        });

        // Return the response with pagination metadata
        return response()->json([
            'data' => $transformedInvoices,
            'pagination' => [
                'total' => $invoices->total(),
                'per_page' => $invoices->perPage(),
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'from' => $invoices->firstItem(),
                'to' => $invoices->lastItem(),
            ],
        ]);
    }

    /**
     * Display the specified invoice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Try to find by regular ID first
            $invoice = Invoice::with(['details', 'payments', 'taxinvoices.tax', 'user', 'shippment'])
                ->find($id);

            // If not found, try to find by hashed ID
            if (!$invoice) {
                $invoice = Invoice::with(['details', 'payments', 'taxinvoices.tax', 'user', 'shippment'])
                    ->findByHashedId($id);
            }

            if (!$invoice) {
                return response()->json(['error' => 'Invoice not found'], 404);
            }

            // Transform the invoice data
            $transformedInvoice = [
                'id' => $invoice->id,
                'hashed_id' => $invoice->hashed_id,
                'date_invoiced' => $invoice->date_invoiced,
                'transaction_currency' => $invoice->transaction_currency,
                'shippment' => $invoice->shippment ? [
                    'id' => $invoice->shippment->id,
                    'number' => $invoice->shippment->number ?? null,
                ] : null,
                'user' => [
                    'id' => $invoice->user->id,
                    'name' => $invoice->user->name,
                ],
                'details' => $invoice->details->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'particular' => $detail->particular,
                        'quantity' => $detail->quantity,
                        'unit_price' => $detail->unit_price,
                        'amount' => $detail->quantity * $detail->unit_price,
                    ];
                }),
                'payments' => $invoice->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'payment_date' => $payment->payment_date,
                        'amount_paid' => $payment->amount_paid,
                        'mode_of_payment' => $payment->mode_of_payment,
                        'narrative' => $payment->narrative,
                    ];
                }),
                'taxes' => $invoice->taxinvoices->map(function ($tax) {
                    return [
                        'id' => $tax->id,
                        'tax_name' => $tax->tax->name,
                        'rate' => $tax->tax->rate,
                        'amount' => ($tax->tax->rate / 100) * $invoice->amount,
                    ];
                }),
                'amount' => $invoice->amount,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'balance' => $invoice->balance,
                'payment_status' => $invoice->balance <= 0 ? 'paid' : 'pending',
                'created_at' => $invoice->created_at,
                'updated_at' => $invoice->updated_at,
            ];

            return response()->json(['data' => $transformedInvoice]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get invoice statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        try {
            $invoices = Invoice::all();

            // Calculate statistics
            $totalInvoices = $invoices->count();
            $paidInvoices = $invoices->filter(function($invoice) {
                return $invoice->balance <= 0 && $invoice->paid_amount > 0;
            })->count();
            $pendingInvoices = $invoices->filter(function($invoice) {
                return $invoice->balance > 0;
            })->count();

            // Calculate amounts by currency
            $totalAmountUSD = $invoices->where('transaction_currency', 'USD')->sum('total_amount');
            $totalAmountUGX = $invoices->where('transaction_currency', 'UGX')->sum('total_amount');

            $paidAmountUSD = $invoices->where('transaction_currency', 'USD')->sum('paid_amount');
            $paidAmountUGX = $invoices->where('transaction_currency', 'UGX')->sum('paid_amount');

            $pendingAmountUSD = $totalAmountUSD - $paidAmountUSD;
            $pendingAmountUGX = $totalAmountUGX - $paidAmountUGX;

            return response()->json([
                'data' => [
                    'total_invoices' => $totalInvoices,
                    'paid_invoices' => $paidInvoices,
                    'pending_invoices' => $pendingInvoices,
                    'amounts' => [
                        'usd' => [
                            'total' => $totalAmountUSD,
                            'paid' => $paidAmountUSD,
                            'pending' => $pendingAmountUSD,
                        ],
                        'ugx' => [
                            'total' => $totalAmountUGX,
                            'paid' => $paidAmountUGX,
                            'pending' => $pendingAmountUGX,
                        ],
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get invoices for finance dashboard with filtering options
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInvoices(Request $request)
    {
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');
        $currency = $request->input('currency', 'all');

        $query = Invoice::with('customer');

        // Filter by status
        if ($status === 'pending') {
            $query->where('payment_status', 'not paid');
        } elseif ($status === 'paid') {
            $query->where('payment_status', 'paid');
        }

        // Filter by currency
        if ($currency !== 'all') {
            $query->where('currency', $currency);
        }

        // Get results
        $invoices = $query->latest()->take(10)->get();

        return response()->json($invoices);
    }

    /**
     * Get quotations of type invoice with filtering options
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getQuotations(Request $request)
    {
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');
        $currency = $request->input('currency', 'all');

        $query = Quotation::with('customer')->where('transaction_type', 'invoice');

        // Filter by status
        if ($status === 'pending') {
            // For pending, we need to check if the balance is greater than 0
            // This is more complex and might require a different approach depending on your data structure
            $quotations = $query->get();
            $pendingQuotations = $quotations->filter(function($quotation) {
                return $quotation->balance($quotation->id) > 0;
            });

            return response()->json($pendingQuotations->values());
        } elseif ($status === 'paid') {
            // For paid, we need to check if the balance is 0 or less
            $quotations = $query->get();
            $paidQuotations = $quotations->filter(function($quotation) {
                return $quotation->balance($quotation->id) <= 0 && $quotation->paid($quotation->id) > 0;
            });

            return response()->json($paidQuotations->values());
        }

        // Filter by currency
        if ($currency !== 'all') {
            $query->where('transaction_currency', $currency);
        }

        // Get results if no status filter or after filtering
        $quotations = $query->latest()->take(10)->get();

        // Calculate total amount for each quotation
        $quotations->each(function($quotation) {
            $quotation->total_amount = $quotation->totalAmount($quotation->id);
            $quotation->paid_amount = $quotation->paid($quotation->id);
            $quotation->balance = $quotation->balance($quotation->id);
        });

        return response()->json($quotations);
    }

    /**
     * Get recent transactions for finance dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function getRecentTransactions()
    {
        // This is a placeholder - in a real implementation, you would fetch actual transaction data
        $transactions = [
            [
                'transaction_date' => now()->subDays(1),
                'description' => 'Invoice Payment #1234',
                'account' => ['name' => 'Bank Account'],
                'transaction_type' => 'income',
                'amount' => 5000,
                'currency' => 'USD'
            ],
            [
                'transaction_date' => now()->subDays(3),
                'description' => 'Office Supplies',
                'account' => ['name' => 'Cash'],
                'transaction_type' => 'expense',
                'amount' => 1200,
                'currency' => 'USD'
            ],
            [
                'transaction_date' => now()->subDays(5),
                'description' => 'Transfer to Savings',
                'account' => ['name' => 'Bank Account'],
                'transaction_type' => 'transfer',
                'amount' => 3000,
                'currency' => 'USD'
            ],
            [
                'transaction_date' => now()->subDays(7),
                'description' => 'Invoice Payment #1233',
                'account' => ['name' => 'Bank Account'],
                'transaction_type' => 'income',
                'amount' => 7500,
                'currency' => 'USD'
            ],
            [
                'transaction_date' => now()->subDays(10),
                'description' => 'Rent Payment',
                'account' => ['name' => 'Bank Account'],
                'transaction_type' => 'expense',
                'amount' => 4000,
                'currency' => 'USD'
            ]
        ];

        return response()->json($transactions);
    }
}
