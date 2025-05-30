<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoicePayment;
use App\Models\Shippment;
use App\Models\ShippmentDocument;
use App\Models\ShippmentTranfer;
use App\Models\Transit;
use App\Models\TransitDocument;
use App\Models\TransitStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransitController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
    {
        if (!Auth::check() || !method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['view_containers'])) {
            return redirect('home');
        }
        
        // Check if this is an AJAX request for DataTables
        if ($request->ajax()) {
            // Get recent containers for dashboard with eager loading
            $transits = Transit::with(['shippment.customer', 'statuses'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            // Log the data for debugging
            \Illuminate\Support\Facades\Log::info('Transit data:', ['count' => $transits->count()]);
                
            $formattedData = [];
            
            foreach ($transits as $transit) {
                // Get the latest status
                $latestStatus = TransitStatus::where('transit_id', $transit->id)
                    ->latest('created_at')
                    ->first();
                    
                $statusName = $latestStatus ? $latestStatus->status : 'Unknown';
                
                // Format status badge
                $statusBadge = '';
                $statusColor = 'primary';
                
                switch (strtolower($statusName)) {
                    case 'in transit':
                        $statusColor = 'info';
                        break;
                    case 'arrived':
                    case 'on port':
                        $statusColor = 'success';
                        break;
                    case 'in warehouse':
                        $statusColor = 'secondary';
                        break;
                    case 'delivered':
                    case 'pickedup':
                        $statusColor = 'dark';
                        break;
                }
                
                $statusBadge = '<div class="badge bg-' . $statusColor . ' rounded-pill">' . $statusName . '</div>';
                
                // Format payment badge
                $paymentBadge = '';
                $paymentStatus = $transit->payment_status ?? 'not_paid';
                
                switch ($paymentStatus) {
                    case 'paid':
                        $paymentBadge = '<div class="badge bg-success rounded-pill">Paid</div>';
                        break;
                    case 'not paid':
                    case 'not_paid':
                        $paymentBadge = '<div class="badge bg-danger rounded-pill">Not Paid</div>';
                        break;
                    case 'partialy paid':
                    case 'partially_paid':
                        $paymentBadge = '<div class="badge bg-warning rounded-pill">Partially Paid</div>';
                        break;
                    default:
                        $paymentBadge = '<div class="badge bg-secondary rounded-pill">Unknown</div>';
                }
                
                // Format actions
                $viewUrl = route('containers.show', $transit->id);
                $editUrl = route('containers.edit', $transit->id);
                $detailsUrl = route('containers.show', $transit->id); // Using the show route for details
                
                $actions = '<div class="d-flex gap-2 justify-content-center">';
                
                // Always include the Details link
                $actions .= '<a href="' . $detailsUrl . '" class="btn btn-sm btn-primary">Details</a>';
                
                // Add other action buttons if user has permissions
                if (Auth::user()->canDo(['edit_container'])) {
                    $actions .= '<a href="' . $editUrl . '" class="btn btn-sm btn-info"><i class="ri-pencil-line"></i></a>';
                }
                
                $actions .= '</div>';
                
                // Get customer name
                $customerName = 'N/A';
                if ($transit->shippment && $transit->shippment->customer) {
                    $customerName = $transit->shippment->customer->name;
                }
                
                // Format date
                $dateArrived = $transit->created_at ? date('d M Y', strtotime($transit->created_at)) : '-';
                
                // Add to formatted data
                $formattedData[] = [
                    'date_arrived' => $dateArrived,
                    'container_number' => $transit->number, // Using the correct field name from the Transit model
                    'customer' => $customerName,
                    'status' => $statusBadge,
                    'payment' => $paymentBadge,
                    'actions' => $actions
                ];
            }
            
            return response()->json($formattedData);
        }
        
        // Regular page view
        // Initialize status counts
        $statusCounts = [
            'On Port' => 0,
            'Pickedup' => 0,
            'In Transit' => 0,
            'In Warehouse' => 0,
            'Transferred' => 0,
        ];

        // Get all transits
        $transits = Transit::orderBy('created_at', 'desc')->get();
        $statusCounts['Total'] = $transits->count();

        foreach ($transits as $transit) {
            $status = Transit::getStatusById($transit->id); // Using the renamed method
            if (isset($statusCounts[$status])) {
                $statusCounts[$status]++;
            }
        }

        // Assign variables for each status
        $onPortCount = $statusCounts['On Port'];
        $pickedUpCount = $statusCounts['Pickedup'];
        $inTransitCount = $statusCounts['In Transit'];
        $inWarehouseCount = $statusCounts['In Warehouse'];
        $transferredCount = $statusCounts['Transferred'];
        $totalCount = $transits->count();

        // Pass the data to the view
        $data = [
            'transits' => $transits,
            'onPortCount' => $onPortCount,
            'pickedUpCount' => $pickedUpCount,
            'inTransitCount' => $inTransitCount,
            'inWarehouseCount' => $inWarehouseCount,
            'transferredCount' => $transferredCount,
            'totalCount' => $totalCount,
            'title' => 'List of Containers',
            'statusCounts' => $statusCounts,
        ];

        return view('transit.container')->with($data);
    }



    // public function index()
    // {
    //     if (!Auth::user()->canDo(['view_containers'])) {
    //         return redirect('home');
    //     }

    //     // Initialize variables
    //     $transitsData = [];
    //     $statusCounts = [
    //         'On Port' => 0,
    //         'Pickedup' => 0,
    //         'In Transit' => 0,
    //         'In Warehouse' => 0,
    //         'Transferred' => 0,
    //     ];

    //     // Process data in chunks of 10
    //     Transit::orderBy('created_at', 'desc')->chunk(10, function ($transits) use (&$transitsData, &$statusCounts) {
    //         foreach ($transits as $transit) {
    //             $transitsData[] = $transit;
    //             // Increment the count based on status
    //             if (isset($statusCounts[$transit->status])) {
    //                 $statusCounts[$transit->status]++;
    //             }
    //         }
    //     });

    //     $totalCount = Transit::count();

    //     // Pass the data to the view
    //     $data = [
    //         'transits' => $transitsData,
    //         'statusCounts' => $statusCounts,
    //         'totalCount' => $totalCount,
    //         'title' => 'List of Containers',
    //     ];

    //     return view('transit.container')->with($data);
    // }

    public function filter(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $transits = Transit::whereBetween('created_at', [$startDate, $endDate])->get();

    $statusCounts = [
        'On Port' => 0,
        'Pickedup' => 0,
        'In Transit' => 0,
        'In Warehouse' => 0,
        'Transferred' => 0,
        'Total' => $transits->count()
    ];

    foreach ($transits as $transit) {
        // Get the latest status for this transit
        $status = TransitStatus::where('transit_id', $transit->id)
            ->latest('created_at')
            ->first();
        $statusName = $status ? $status->status : 'N/A';
        if (isset($statusCounts[$statusName])) {
            $statusCounts[$statusName]++;
        }
    }

    return response()->json(['statusCounts' => $statusCounts]);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if(!Auth::check() || !method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['create_containers'])) return redirect('home');

        $categories = ["Normal", "Mixed", "Kitagululo", "FCL"];

        $data = [
             'title'=>'Create container',
             'categories'=>$categories,
        ];

        return view('transit.create_container')->with($data);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'number'=>'required|unique:transits'
        ];

        $this->validate($request,$rules);

        $saveTransit = new Transit();

        $saveTransit->number = $request->number;

        $saveTransit->user_id = Auth::id();

        $saveTransit->category = $request->category;

        $saveTransit->size = $request->size;

        $saveTransit->expected_arrival_time = $request->expected_arrival_time;

        $saveTransit->departure_place = $request->departure_place;

        $saveTransit->destination_place = $request->destination_place;

        $saveTransit->contact_information = $request->contact_information;

        $saveTransit->clearing_agent = $request->clearing_agent;

        $saveTransit->entry_number = $request->entry_number;

        $saveTransit->save();

        return redirect()->route('containers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($transit_id)
    {

        if(!Auth::check() || !method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['view_shipment'])) return redirect('home');

        $transit = Transit::find($transit_id);

        $status = [
            'On Port','Pickedup','In Transit','In Warehouse','Transferred'
        ];

        $data = [
            'transit'=>$transit,
            'title'=>$transit->number,
            'statuses'=>$status,
            'shippments'=>$transit->shippments,
        ];

        return view('transit.details')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($transit_id)
    {

        if(!Auth::check() || !method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['edit_containers'])) return redirect('home');

        $categories = ["Normal", "Mixed", "Kitagululo", "FCL"];

        $payments = ['paid','not paid','partialy paid'];

        $transit = Transit::find($transit_id);

        $data = [
            'transit'=>$transit,
            'title'=>'Edit Container',
            'categories'=>$categories,
            'payments'=>$payments,
        ];

        return view('transit.edit_transit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$transit_id)
    {
        $saveTransit = Transit::find($transit_id);

        $saveTransit->number = $request->number;

        $saveTransit->user_id = Auth::id();

        $saveTransit->payment_status = $request->payment_status;

        $saveTransit->category = $request->category;

        $saveTransit->size = $request->size;

        $saveTransit->expected_arrival_time = $request->expected_arrival_time;

        $saveTransit->departure_place = $request->departure_place;

        $saveTransit->destination_place = $request->destination_place;

        $saveTransit->contact_information = $request->contact_information;

        $saveTransit->clearing_agent = $request->clearing_agent;

        $saveTransit->entry_number = $request->entry_number;

        $saveTransit->save();

        return redirect()->route('containers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($transit_id)
    {

        $transit = Transit::find($transit_id);

        try {

            $shipments = Shippment::where('transit_id',$transit->id)->pluck('id')->toArray();

            $invoices = Invoice::whereIn('shippment_id',$shipments)->pluck('id')->toArray();

            ShippmentTranfer::whereIn('shippment_id',$shipments)->delete();

            ShippmentDocument::whereIn('shippment_id',$shipments)->delete();

            InvoiceDetail::whereIn('invoice_id',$invoices)->delete();

            InvoicePayment::whereIn('invoice_id',$invoices)->delete();

            Invoice::whereIn('shippment_id',$shipments)->delete();

            Shippment::where('transit_id',$transit->id)->delete();

            TransitStatus::where('transit_id',$transit->id)->delete();

            TransitDocument::where('transit_id',$transit->id)->delete();

            Transit::destroy($transit->id);

        } catch (\Throwable $th) {}

        return redirect()->route('containers.index');

    }

    /**
     * Mark a transit as paid
     */
    public function markAsPaid(Transit $transit)
    {
        if(!Auth::check() || !method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['edit_containers'])) {
            return redirect()->back()->with('error', 'You do not have permission to perform this action.');
        }

        $transit->payment_status = 'paid';
        $transit->save();

        return redirect()->route('containers.show', $transit->id)->with('success', 'Payment status updated to paid.');
    }

    /**
     * Get payment status counts for containers
     */
    public function paymentStatusCount()
    {
        // Count containers by payment status
        $paid = Transit::where('payment_status', 'paid')->count();
        $notPaid = Transit::where('payment_status', 'not paid')->orWhereNull('payment_status')->count();

        return response()->json([
            'paid' => $paid,
            'not_paid' => $notPaid
        ]);
    }



    /**
     * Display detailed container information with related data
     */
    public function details(Transit $transit)
    {
        if(!Auth::check() || !method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['view_containers'])) {
            return redirect('home');
        }

        // Get all shipments for this container
        $shipments = $transit->shippments()->with('customer', 'agent')->get();

        // Get all shipment IDs for this container
        $shipmentIds = $shipments->pluck('id')->toArray();

        // Get all invoices related to these shipments
        $invoices = Invoice::whereIn('shippment_id', $shipmentIds)
            ->with(['details', 'payments', 'shippment.customer'])
            ->get();

        // Get all payments related to these invoices
        $payments = InvoicePayment::whereIn('invoice_id', $invoices->pluck('id')->toArray())->get();

        // Get all transfers related to these shipments
        $transfers = ShippmentTranfer::whereIn('shippment_id', $shipmentIds)->get();

        // Get all documents for this container
        $containerDocuments = $transit->documents;

        // Get all shipment documents
        $shipmentDocuments = ShippmentDocument::whereIn('shippment_id', $shipmentIds)->get();

        // Get all statuses for this container
        $statuses = $transit->statuses()->orderBy('created_at', 'desc')->get();

        // Calculate totals with error handling
        try {
            $totalInvoiced = 0;
            $totalPaid = 0;

            // Calculate invoice totals safely
            foreach ($invoices as $invoice) {
                try {
                    // Use the total_amount property if it exists, otherwise calculate it
                    if (isset($invoice->total_amount) && is_numeric($invoice->total_amount)) {
                        $totalInvoiced += $invoice->total_amount;
                    } else if (method_exists($invoice, 'totalAmount')) {
                        $totalInvoiced += $invoice->totalAmount($invoice->id) ?: 0;
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating invoice amount for invoice ID: ' . $invoice->id . ' - ' . $e->getMessage());
                }
            }

            // Calculate payment totals safely
            foreach ($payments as $payment) {
                try {
                    $totalPaid += $payment->amount ?: 0;
                } catch (\Exception $e) {
                    Log::error('Error calculating payment amount for payment ID: ' . $payment->id . ' - ' . $e->getMessage());
                }
            }

            $balance = $totalInvoiced - $totalPaid;
        } catch (\Exception $e) {
            Log::error('Error calculating container financial totals: ' . $e->getMessage());
            $totalInvoiced = 0;
            $totalPaid = 0;
            $balance = 0;
        }

        $data = [
            'transit' => $transit,
            'title' => 'Container: ' . $transit->number,
            'shipments' => $shipments,
            'invoices' => $invoices,
            'payments' => $payments,
            'transfers' => $transfers,
            'containerDocuments' => $containerDocuments,
            'shipmentDocuments' => $shipmentDocuments,
            'statuses' => $statuses,
            'totalInvoiced' => $totalInvoiced,
            'totalPaid' => $totalPaid,
            'balance' => $balance,
        ];

        return view('transit.container_details')->with($data);
    }
    
    /**
     * Get container data for DataTables server-side processing
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            // Check if user has permission to view containers
            // For dashboard view, we'll allow access without strict permission checks
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Skip permission check for dashboard view
            if (!$request->has('custom_param') || $request->input('custom_param') !== 'dashboard_view') {
                // Only check permissions for non-dashboard views
                if (!method_exists(Auth::user(), 'canDo') || !Auth::user()->canDo(['view_container'])) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
            }
            
            // Start query builder
            $query = Transit::with(['shippment.customer'])
                ->select('transits.*');
                
            // Apply search filter if provided
            if ($request->has('search') && !empty($request->input('search.value'))) {
                $searchValue = $request->input('search.value');
                $query->where(function($q) use ($searchValue) {
                    $q->where('container_number', 'LIKE', "%{$searchValue}%")
                      ->orWhere('payment_status', 'LIKE', "%{$searchValue}%")
                      ->orWhereHas('status', function($q2) use ($searchValue) {
                          $q2->where('name', 'LIKE', "%{$searchValue}%");
                      })
                      ->orWhereHas('shippment.customer', function($q2) use ($searchValue) {
                          $q2->where('name', 'LIKE', "%{$searchValue}%");
                      });
                });
            }
            
            // Get total count before applying filters
            $totalRecords = Transit::count();
            
            // Get filtered count after applying search
            $filteredRecords = $query->count();
            
            // Apply ordering
            if ($request->has('order') && is_array($request->input('order'))) {
                $columnIndex = $request->input('order.0.column');
                $columnDir = $request->input('order.0.dir');
                
                // Define column mapping
                $columns = [
                    0 => 'id',
                    1 => 'container_number',
                    2 => 'customer_name', // This will need special handling
                    3 => 'date_arrived',
                    4 => 'status_id',
                    5 => 'payment_status'
                ];
                
                if (isset($columns[$columnIndex])) {
                    $columnName = $columns[$columnIndex];
                    
                    if ($columnName === 'customer_name') {
                        $query->join('shippments', 'transits.shippment_id', '=', 'shippments.id')
                              ->join('customers', 'shippments.customer_id', '=', 'customers.id')
                              ->orderBy('customers.name', $columnDir);
                    } else {
                        $query->orderBy($columnName, $columnDir);
                    }
                }
            } else {
                // Default ordering
                $query->orderBy('id', 'desc');
            }
            
            // Apply pagination
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $query->offset($start)->limit($length);
            
            // Get results
            $containers = $query->get();
            
            // Format data for DataTables
            $data = [];
            foreach ($containers as $container) {
                // Get customer name
                $customerName = $container->shippment && $container->shippment->customer 
                    ? $container->shippment->customer->name 
                    : 'N/A';
                
                // Get the latest status for this container
                $latestStatus = TransitStatus::where('transit_id', $container->id)
                    ->latest('created_at')
                    ->first();
                
                // Format status badge
                $statusBadge = '';
                if ($latestStatus) {
                    $statusColor = 'primary';
                    $statusName = $latestStatus->status;
                    
                    switch (strtolower($statusName)) {
                        case 'in transit':
                            $statusColor = 'info';
                            break;
                        case 'arrived':
                        case 'on port':
                            $statusColor = 'success';
                            break;
                        case 'in warehouse':
                            $statusColor = 'secondary';
                            break;
                        case 'delivered':
                        case 'pickedup':
                            $statusColor = 'dark';
                            break;
                    }
                    $statusBadge = '<div class="badge bg-' . $statusColor . ' rounded-pill">' . $statusName . '</div>';
                } else {
                    $statusBadge = '<div class="badge bg-secondary rounded-pill">Unknown</div>';
                }
                
                // Format payment status badge
                $paymentStatusBadge = '';
                switch ($container->payment_status) {
                    case 'paid':
                        $paymentStatusBadge = '<div class="badge bg-success rounded-pill">Paid</div>';
                        break;
                    case 'not_paid':
                        $paymentStatusBadge = '<div class="badge bg-danger rounded-pill">Not Paid</div>';
                        break;
                    default:
                        $paymentStatusBadge = '<div class="badge bg-secondary rounded-pill">Unknown</div>';
                }
                
                // Format action buttons
                $viewUrl = route('containers.show', $container->id);
                $editUrl = route('containers.edit', $container->id);
                $detailsUrl = route('containers.details', $container->id);
                
                $actions = '<div class="d-flex gap-2 justify-content-center">';
                if (Auth::user()->canDo(['view_container'])) {
                    $actions .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i></a>';
                }
                if (Auth::user()->canDo(['edit_container'])) {
                    $actions .= '<a href="' . $editUrl . '" class="btn btn-sm btn-info"><i class="ri-pencil-line"></i></a>';
                }
                if (Auth::user()->canDo(['view_container'])) {
                    $actions .= '<a href="' . $detailsUrl . '" class="btn btn-sm btn-secondary"><i class="ri-information-line"></i></a>';
                }
                $actions .= '</div>';
                
                // Format date
                $dateArrived = $container->date_arrived ? date('d M Y', strtotime($container->date_arrived)) : '-';
                
                // Add to data array
                $data[] = [
                    $container->id,
                    $container->container_number,
                    $customerName,
                    $dateArrived,
                    $statusBadge,
                    $paymentStatusBadge,
                    $actions
                ];
            }
            
            // Return response in DataTables format
            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('DataTables error: ' . $e->getMessage());
            
            // Return error response
            return response()->json([
                'error' => true,
                'message' => 'An error occurred while processing your request: ' . $e->getMessage(),
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 500);
        }
    }
}
