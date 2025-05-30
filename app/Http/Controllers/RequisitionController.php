<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Requisition;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\IdHasher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function datatable(Request $request)
    {
        try {
            $userId = Auth::id();
            $userType = Auth::user()->user_type;
            $statusFilter = $request->input('status', '');

            // Log the request for debugging
            \Log::info('DataTable Request:', [
                'user_id' => $userId,
                'user_type' => $userType,
                'status_filter' => $statusFilter,
                'all_params' => $request->all()
            ]);

            // Disable ONLY_FULL_GROUP_BY for this session
            DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        // Base query with only necessary relationships
        $query = Requisition::with([
            'user:id,name,email',
            'approvals:id,requisition_id,status',
            'details:id,requisition_id,estimated_cost,quantity,particular,reason'
        ]);
        
        // Calculate totals using subqueries to avoid N+1 queries
        $query->withSum(['details as total_estimated_cost' => function($q) {
            $q->select(DB::raw('SUM(estimated_cost * quantity)'));
        }], 'estimated_cost');

        // Apply user filter for regular users
        if (!in_array($userType, ['Admin', 'MD', 'Manager', 'Accountant'])) {
            $query->where('user_id', $userId);
        }

        // Apply status filter
        if (!empty($statusFilter)) {
            if ($statusFilter === 'pending') {
                $query->where(function($query) {
                    $query->whereDoesntHave('approvals')
                        ->orWhere(function($q) {
                            // Get requisitions where not all approvals are approved
                            $q->whereHas('approvals', function($sq) {
                                $sq->where('status', '!=', 'approved');
                            })
                            // And no rejections
                            ->whereDoesntHave('approvals', function($sq) {
                                $sq->where('status', 'rejected');
                            });
                        });
                });
            } elseif ($statusFilter === 'approved') {
                $query->whereHas('approvals')
                    ->whereDoesntHave('approvals', function($q) {
                        $q->where('status', '!=', 'approved');
                    });
            } elseif ($statusFilter === 'rejected') {
                $query->whereHas('approvals', function($q) {
                    $q->where('status', 'rejected');
                });
            }
        }

            // Log the query for debugging
            \Log::info('Executing DataTable query', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

            // Return the DataTables response
            $dataTable = DataTables::eloquent($query)
                ->filter(function($query) use ($request) {
                // Handle search
                if ($request->has('search') && !empty($request->input('search')['value'])) {
                    $search = $request->input('search')['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                    });
                }
            })
            ->order(function($query) use ($request) {
                // Handle ordering
                if ($request->has('order') && count($request->input('order'))) {
                    $orderColumn = $request->input('order')[0]['column'];
                    $orderDir = $request->input('order')[0]['dir'];
                    $columns = $request->input('columns');
                    
                    if (isset($columns[$orderColumn]['data'])) {
                        $orderBy = $columns[$orderColumn]['data'];
                        $query->orderBy($orderBy, $orderDir);
                    }
                }
            })
            ->addColumn('date_requisitioned', function($requisition) {
                try {
                    // Ensure we have a Carbon instance
                    $date = $requisition->date_requisitioned;
                    if (is_string($date)) {
                        $date = \Carbon\Carbon::parse($date);
                    }
                    return $date->format('d M Y');
                } catch (\Exception $e) {
                    \Log::error('Error formatting date:', [
                        'requisition_id' => $requisition->id,
                        'date' => $requisition->date_requisitioned,
                        'error' => $e->getMessage()
                    ]);
                    return 'Invalid date';
                }
            })
            ->addColumn('user', function($requisition) {
                return $requisition->user->name;
            })
            ->addColumn('status', function($requisition) {
                $status = $requisition->status($requisition->id);
                $statusClass = [
                    'approved' => 'bg-success',
                    'pending' => 'bg-warning',
                    'rejected' => 'bg-danger'
                ][$status] ?? '';

                $statusIcon = [
                    'approved' => 'ri-checkbox-circle-line',
                    'pending' => 'ri-time-line',
                    'rejected' => 'ri-close-circle-line'
                ][$status] ?? '';

                return '<span class="badge '.$statusClass.' text-white">
                    <i class="'.$statusIcon.' me-8"></i> '.ucfirst($status).'
                </span>';
            })
            ->addColumn('remarks', function($requisition) {
                return $requisition->details->first()?->remarks ?: 'N/A';
            })
            ->addColumn('currency', function($requisition) {
                return $requisition->details->first()?->currency ?: 'UGX';
            })
            ->addColumn('amount', function($requisition) {
                $total = $requisition->details->sum(function($detail) {
                    return $detail->estimated_cost * $detail->quantity;
                });
                return number_format($total, 2);
            })
            ->addColumn('paid', function($requisition) {
                // If you have a payments table, you can calculate paid amount here
                // For now, we'll return 0 as paid amount
                return number_format(0, 2);
            })
            ->addColumn('balance', function($requisition) {
                $total = $requisition->details->sum(function($detail) {
                    return $detail->estimated_cost * $detail->quantity;
                });
                $class = $total > 0 ? 'text-danger' : 'text-success';
                return '<span class="'.$class.'">'.number_format($total, 2).'</span>';
            })
            ->addColumn('actions', function($requisition) {
                $viewBtn = '<a href="'.route('requisitions.secure.show', $requisition->hashed_id).'" 
                    class="btn btn-sm btn-primary" title="View Details">
                    <i class="ri-eye-line"></i> View
                </a>';

                $deleteBtn = '';
                if ($requisition->status($requisition->id) !== 'approved') {
                    $deleteBtn = '<form action="'.route('requisitions.destroy', $requisition->id).'" 
                        method="POST" style="display:inline;">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger" 
                            title="Delete" onclick="return confirm(\'Are you sure?\')">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>';
                }

                return '<div class="d-flex gap-4">'.$viewBtn.$deleteBtn.'</div>';
            })
                ->rawColumns(['status', 'balance', 'actions']);

            // Log the response
            $response = $dataTable->toJson();
            \Log::info('DataTable response', ['response' => $response]);
            
            return $response;
        } catch (\Exception $e) {
            // Log the full error
            \Log::error('DataTable Error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a proper error response
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'An error occurred while loading the data. Please try again later.'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        // Check if this is an AJAX request from DataTables
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        // Get the current authenticated user's ID and user_type
        $userId = Auth::id();
        $userType = Auth::user()->user_type;
        $statusFilter = $request->input('status', '');
        $cacheKey = "requisition_counts_{$userId}_{$statusFilter}";

        // Use cache for counts (5 minutes)
        $counts = \Cache::remember($cacheKey, 300, function() use ($userId, $userType) {
            $baseQuery = Requisition::query();

            // Apply user filter for regular users
            if (!in_array($userType, ['Admin', 'MD', 'Manager', 'Accountant'])) {
                $baseQuery->where('user_id', $userId);
            }

            // Get all requisitions with their statuses
            $requisitions = (clone $baseQuery)->with('approvals')->get();
            
            // If no requisitions, return zeros
            if ($requisitions->isEmpty()) {
                return [
                    'total' => 0,
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0
                ];
            }

            // Initialize counters
            $pending = 0;
            $approved = 0;
            $rejected = 0;

            // Categorize each requisition using the model's status method
            foreach ($requisitions as $requisition) {
                $status = $requisition->status($requisition->id);
                
                switch ($status) {
                    case 'approved':
                        $approved++;
                        break;
                    case 'rejected':
                        $rejected++;
                        break;
                    case 'pending':
                    default:
                        $pending++;
                        break;
                }
            }

            return [
                'total' => $requisitions->count(),
                'pending' => $pending,
                'approved' => $approved,
                'rejected' => $rejected
            ];
        });

        // Extract counts from cached result
        $totalCount = $counts['total'];
        $pendingCount = $counts['pending'];
        $approvedCount = $counts['approved'];
        $rejectedCount = $counts['rejected'];

        // Return the view with just the counts - data will be loaded via AJAX
        return view('requisitions.list', [
            'title' => 'Requisitions',
            'totalCount' => $totalCount,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'statusFilter' => $statusFilter
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $saveRequisition = new Requisition();

        $saveRequisition->user_id = Auth::id();

        $saveRequisition->date_requisitioned = $request->date_requisitioned;

        $saveRequisition->currency = $request->currency;

        $saveRequisition->save();

        return redirect()->route('requisitions.secure.show', $saveRequisition->hashed_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Requisition $requisition)
    {
        // Eager load relationships to reduce database queries
        $requisition->load(['details', 'details.category', 'documents', 'user']);

        // Pre-calculate expensive attributes
        $requisition->calculated_amount = $requisition->amountCached($requisition->id);
        $requisition->calculated_paid = $requisition->paidCached($requisition->id);
        $requisition->calculated_balance = $requisition->balanceCached($requisition->id);

        $status = ['pending', 'approved', 'rejected'];

        // Use optimized approvers method
        $approvers = $requisition->approvers($requisition->id);

        // Get expense categories and suppliers (consider caching these if they don't change often)
        $expense_categories = ExpenseCategory::get();
        $suppliers = Supplier::get();

        $mode_of_payments = ['Cash','Bank','Mobile Money','Digital'];

        // Use paginated payments to improve performance with large datasets
        $payments = $requisition->paymentsWithPagination($requisition->id, 10);

        $data = [
            'requisition' => $requisition,
            'title' => 'Requisition No. ' . str_pad($requisition->id, 4, '0', STR_PAD_LEFT),
            'statuses' => $status,
            'documents' => $requisition->documents,
            'approvers' => $approvers,
            'expense_categories' => $expense_categories,
            'mode_of_payments' => $mode_of_payments,
            'suppliers' => $suppliers,
            'payments' => $payments,
        ];

        return view('requisitions.details')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requisition $requisition)
    {

        if (!Auth::user()->canDo(['convert_requisition_to_expense'])) return redirect('home');

        foreach ($requisition->details as $detail) {

            Expense::saveExpense($detail->particular, $detail->reason, $detail->quantity, $detail->estimated_cost, $detail->requisition_id);
        }

        return redirect()->route('expenses.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requisition $requisition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requisition $requisition)
    {
        // Check if the requisition status is not 'approved'
        if (ucfirst($requisition->status($requisition->id)) !== 'Approved') {
            // Delete associated details first
            $requisition->details()->delete();

            // Then delete the requisition itself
            $requisition->delete();

            // Redirect back with success message
            return redirect()->route('requisitions.index')->with('success', 'Requisition deleted successfully.');
        }

        // Redirect back with error message for approved requisitions
        return redirect()->route('requisitions.index')->with('error', 'Approved requisitions cannot be deleted.');
    }


    function printRequisition($requisition_id){
        // Find requisition with eager loading to reduce database queries
        $requisition = Requisition::with(['details', 'details.category', 'documents', 'user'])
            ->find($requisition_id);

        if (!$requisition) {
            abort(404);
        }

        // Pre-calculate expensive attributes
        $requisition->calculated_amount = $requisition->amountCached($requisition->id);
        $requisition->calculated_paid = $requisition->paidCached($requisition->id);
        $requisition->calculated_balance = $requisition->balanceCached($requisition->id);

        // Use optimized approvers method
        $approvers = $requisition->approvers($requisition->id);

        // Use optimized payments method (limit to 100 for printing)
        $payments = $requisition->payments($requisition->id);

        $data = [
            'requisition' => $requisition,
            'title' => 'Requisition No. ' . str_pad($requisition->id, 4, '0', STR_PAD_LEFT),
            'documents' => $requisition->documents,
            'approvers' => $approvers,
            'payments' => $payments,
        ];

        return view('requisitions.print_optimized')->with($data);
    }

    /**
     * Show requisition using encrypted ID
     *
     * @param string $hashedId
     * @return \Illuminate\Http\Response
     */
    public function showEncrypted($hashedId)
    {
        $requisition = Requisition::findByHashedId($hashedId);

        if (!$requisition) {
            abort(404);
        }

        return $this->show($requisition);
    }

    /**
     * Print requisition using encrypted ID
     *
     * @param string $hashedId
     * @return \Illuminate\Http\Response
     */
    public function printEncrypted($hashedId)
    {
        $requisition = Requisition::findByHashedId($hashedId);

        if (!$requisition) {
            abort(404);
        }

        return $this->printRequisition($requisition->id);
    }

    /**
     * Generate PDF for requisition
     *
     * @param int $requisition_id
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($requisition_id)
    {
        // Find requisition with eager loading to reduce database queries
        $requisition = Requisition::with(['details', 'details.category', 'documents', 'user'])
            ->find($requisition_id);

        if (!$requisition) {
            abort(404);
        }

        // Pre-calculate expensive attributes
        $requisition->calculated_amount = $requisition->amountCached($requisition->id);
        $requisition->calculated_paid = $requisition->paidCached($requisition->id);
        $requisition->calculated_balance = $requisition->balanceCached($requisition->id);

        // Use optimized approvers method
        $approvers = $requisition->approvers($requisition->id);

        // Use optimized payments method
        $payments = $requisition->payments($requisition->id);

        $data = [
            'requisition' => $requisition,
            'title' => 'Requisition No. ' . str_pad($requisition->id, 4, '0', STR_PAD_LEFT),
            'documents' => $requisition->documents,
            'approvers' => $approvers,
            'payments' => $payments,
        ];

        $pdf = PDF::loadView('requisitions.print_optimized', $data);

        return $pdf->download('requisition-' . str_pad($requisition->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Generate PDF for requisition using encrypted ID
     *
     * @param string $hashedId
     * @return \Illuminate\Http\Response
     */
    public function pdfEncrypted($hashedId)
    {
        $requisition = Requisition::findByHashedId($hashedId);

        if (!$requisition) {
            abort(404);
        }

        return $this->generatePdf($requisition->id);
    }

    /**
     * Generate a public PDF for requisition that doesn't require authentication
     *
     * @param string $hashedId
     * @return \Illuminate\Http\Response
     */
    public function publicPdf($hashedId)
    {
        // Find requisition with eager loading to reduce database queries
        $requisition = Requisition::with(['details', 'details.category', 'documents', 'user'])
            ->findByHashedId($hashedId);

        if (!$requisition) {
            abort(404);
        }

        // Pre-calculate expensive attributes
        $requisition->calculated_amount = $requisition->amountCached($requisition->id);
        $requisition->calculated_paid = $requisition->paidCached($requisition->id);
        $requisition->calculated_balance = $requisition->balanceCached($requisition->id);

        // Use optimized approvers method
        $approvers = $requisition->approvers($requisition->id);

        // Use optimized payments method
        $payments = $requisition->payments($requisition->id);

        $data = [
            'requisition' => $requisition,
            'title' => 'Requisition No. ' . str_pad($requisition->id, 4, '0', STR_PAD_LEFT),
            'documents' => $requisition->documents,
            'approvers' => $approvers,
            'payments' => $payments,
            'current_date' => Carbon::now()->format('d M Y H:i:s'),
            'is_public' => true
        ];

        $pdf = PDF::loadView('requisitions.public_pdf', $data);

        return $pdf->download('requisition-' . str_pad($requisition->id, 4, '0', STR_PAD_LEFT) . '.pdf');
    }
}
