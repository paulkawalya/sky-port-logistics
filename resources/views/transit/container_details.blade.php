@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-checkbox-circle-line me-4"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-4"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-8">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Comprehensive container tracking information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('containers.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back to Containers
            </a>
            @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['view_shipment']))
                <a href="{{ route('containers.show', $transit->id) }}" class="btn btn-outline-primary">
                    <i class="ri-eye-line me-8"></i> View Basic Details
                </a>
            @endif
            @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['edit_containers']))
                <a href="{{ route('containers.edit', $transit->id) }}" class="btn btn-primary">
                    <i class="ri-edit-line me-8"></i> Edit Container
                </a>
            @endif
            @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['edit_containers']) && $transit->payment_status != 'paid')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mark_as_paid_modal">
                    <i class="ri-checkbox-circle-line me-8"></i> Mark as Paid
                </button>
            @endif
        </div>
    </div>

    <!-- Container Summary Section -->
    <div class="row mb-8">
        <!-- Container Info & Financial Summary Card -->
        <div class="col-md-8 mb-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-dashboard-line me-4 text-primary"></i>Container Summary
                    </h5>
                    @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['view_container_updates']))
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#update_status">
                            <i class="ri-refresh-line me-8"></i> Update Status
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Container Info -->
                        <div class="col-md-6 border-end">
                            <div class="d-flex align-items-center mb-8">
                                <div style="width: 48px; height: 48px; border-radius: 8px; background-color: rgba(0, 99, 247, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                                    <i class="ri-ship-line" style="color: #0063f7; font-size: 24px;"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">{{ $transit->number }}</h5>
                                    <p class="text-muted small mb-0">Created: {{ $transit->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <div class="row g-4 mb-8">
                                <div class="col-6">
                                    <div class="p-8 rounded bg-light">
                                        <p class="text-muted mb-1 small">Category</p>
                                        <p class="mb-0 fw-medium">{{ $transit->category }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-8 rounded bg-light">
                                        <p class="text-muted mb-1 small">Size</p>
                                        <p class="mb-0 fw-medium">{{ $transit->size ?: 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-8 rounded bg-light">
                                        <p class="text-muted mb-1 small">Freight Value</p>
                                        <p class="mb-0 fw-medium">{{ $transit->entry_number ?: 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-8 rounded bg-light" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 110, 253, 0.1)) !important;">
                                        <p class="text-muted mb-1 small">Payment Status</p>
                                        <p class="mb-0 fw-medium">
                                            @if($transit->payment_status == 'paid')
                                                <span class="badge bg-success-subtle text-success px-2 py-1">Paid</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning px-2 py-1">Pending</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-8 rounded bg-light">
                                        <p class="text-muted mb-1 small">Departure</p>
                                        <p class="mb-0 fw-medium">{{ $transit->departure_place ?: 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-8 rounded bg-light">
                                        <p class="text-muted mb-1 small">Destination</p>
                                        <p class="mb-0 fw-medium">{{ $transit->destination_place ?: 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-muted small">Expected Arrival:</span>
                                <span class="fw-medium">{{ $transit->expected_arrival_time ?: 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-muted small">Transporter:</span>
                                <span class="fw-medium">{{ $transit->contact_information ?: 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-muted small">Clearing Agent:</span>
                                <span class="fw-medium">{{ $transit->clearing_agent ?: 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Created By:</span>
                                <span class="fw-medium">{{ $transit->user->name }}</span>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="col-md-6">
                            <!-- Status Badge -->
                            <div class="mb-8">

                                @php
                                    $currentStatus = $statuses->first() ? $statuses->first()->status : 'Unknown';
                                    $statusColors = [
                                        'On Port' => ['bg-info-subtle', 'text-info'],
                                        'Pickedup' => ['bg-warning-subtle', 'text-warning'],
                                        'In Transit' => ['bg-primary-subtle', 'text-primary'],
                                        'In Warehouse' => ['bg-success-subtle', 'text-success'],
                                        'Transferred' => ['bg-secondary-subtle', 'text-secondary'],
                                    ];
                                    $colorClasses = $statusColors[$currentStatus] ?? ['bg-light', 'text-dark'];
                                @endphp
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $colorClasses[0] }} {{ $colorClasses[1] }} px-3 py-2 rounded-pill me-4">
                                        {{ $currentStatus }}
                                    </span>
                                    <span class="text-muted small">
                                        @if($statuses->first())
                                            Updated {{ $statuses->first()->created_at->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Financial Cards -->
                            <div class="mb-8">
                                <h6 class="fw-bold mb-8">Financial Summary</h6>

                                <!-- Total Invoiced Card -->
                                <div class="financial-card mb-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center p-8"
                                                     style="background: linear-gradient(135deg, #28a745, #20c997); border-radius: 8px 0 0 8px; width: 80px;">
                                                    <i class="ri-file-list-3-line text-white" style="font-size: 28px;"></i>
                                                </div>
                                                <div class="p-8 w-100">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="text-muted mb-1">Total Invoiced</h6>
                                                            <h4 class="mb-0 fw-bold">{{ number_format($totalInvoiced) }} <span class="fs-6 fw-normal">UGX</span></h4>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                                <i class="ri-arrow-up-line me-8"></i> {{ $invoices->count() }} Invoice(s)
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Paid Card -->
                                <div class="financial-card mb-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center p-8"
                                                     style="background: linear-gradient(135deg, #0d6efd, #0dcaf0); border-radius: 8px 0 0 8px; width: 80px;">
                                                    <i class="ri-bank-card-line text-white" style="font-size: 28px;"></i>
                                                </div>
                                                <div class="p-8 w-100">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="text-muted mb-1">Total Paid</h6>
                                                            <h4 class="mb-0 fw-bold">{{ number_format($totalPaid) }} <span class="fs-6 fw-normal">UGX</span></h4>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">
                                                                <i class="ri-arrow-up-line me-8"></i> {{ $payments->count() }} Payment(s)
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Balance Card -->
                                <div class="financial-card mb-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center p-8"
                                                     style="background: linear-gradient(135deg, #dc3545, #fd7e14); border-radius: 8px 0 0 8px; width: 80px;">
                                                    <i class="ri-scales-3-line text-white" style="font-size: 28px;"></i>
                                                </div>
                                                <div class="p-8 w-100">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="text-muted mb-1">Balance</h6>
                                                            <h4 class="mb-0 fw-bold">{{ number_format($balance) }} <span class="fs-6 fw-normal">UGX</span></h4>
                                                        </div>
                                                        <div class="text-end">
                                                            @if($balance > 0)
                                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">
                                                                    <i class="ri-error-warning-line me-8"></i> Outstanding
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                                    <i class="ri-check-line me-8"></i> Fully Paid
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Profit Calculation Card -->
                                <div class="financial-card">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 d-flex align-items-center justify-content-center p-8"
                                                     style="background: linear-gradient(135deg, #20c997, #0dcaf0); border-radius: 8px 0 0 8px; width: 80px;">
                                                    <i class="ri-line-chart-fill text-white" style="font-size: 28px;"></i>
                                                </div>
                                                <div class="p-8 w-100">
                                                    @php
                                                        $freightValue = is_numeric($transit->entry_number) ? intval($transit->entry_number) : 0;
                                                        $potentialProfit = $totalInvoiced - $freightValue;
                                                        $profitMargin = $freightValue > 0 ? ($potentialProfit / $freightValue) * 100 : 0;
                                                    @endphp
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="text-muted mb-1">Potential Profit</h6>
                                                            <h4 class="mb-0 fw-bold">{{ number_format($potentialProfit) }} <span class="fs-6 fw-normal">UGX</span></h4>
                                                        </div>
                                                        <div class="text-end">
                                                            @if($potentialProfit > 0)
                                                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                                                    <i class="ri-arrow-up-line me-8"></i> {{ number_format($profitMargin, 1) }}% Margin(Frieght VS Invoices)
                                                                </span>
                                                            @elseif($potentialProfit < 0)
                                                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">
                                                                    <i class="ri-arrow-down-line me-8"></i> Loss
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">
                                                                    <i class="ri-subtract-line me-8"></i> Break-even
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Chart Card -->
        <div class="col-md-4 mb-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-line-chart-line me-4 text-info"></i>Financial Comparison
                    </h5>
                    <div class="chart-legend d-flex gap-4">
                        <div class="d-flex align-items-center">
                            <span class="legend-indicator" style="width: 10px; height: 10px; background-color: #198754; border-radius: 50%; display: inline-block; margin-right: 5px;"></span>
                            <span class="small">Invoiced</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="legend-indicator" style="width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; display: inline-block; margin-right: 5px;"></span>
                            <span class="small">Paid</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="legend-indicator" style="width: 10px; height: 10px; background-color: #dc3545; border-radius: 50%; display: inline-block; margin-right: 5px;"></span>
                            <span class="small">Freight</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <div id="finance-chart" class="apex-chart"></div>
                    </div>

                    <!-- Payment Progress -->
                    <div class="mt-3 mb-8">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted">Payment Progress</span>
                            <span class="small fw-medium">{{ $totalPaid > 0 && $totalInvoiced > 0 ? round(($totalPaid / $totalInvoiced) * 100) : 0 }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar"
                                 style="width: {{ $totalPaid > 0 && $totalInvoiced > 0 ? min(($totalPaid / $totalInvoiced) * 100, 100) : 0 }}%;"
                                 aria-valuenow="{{ $totalPaid > 0 && $totalInvoiced > 0 ? round(($totalPaid / $totalInvoiced) * 100) : 0 }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Status Timeline -->
                    <div class="status-timeline">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0">Recent Status Updates</h6>
                            @if($statuses->count() > 3)
                                <button class="btn btn-sm btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#moreStatuses" aria-expanded="false" aria-controls="moreStatuses">
                                    <i class="ri-arrow-down-s-line"></i> Show all
                                </button>
                            @endif
                        </div>

                        <div class="timeline-container" style="max-height: 180px; overflow-y: auto;">
                            @foreach($statuses->take(3) as $status)
                                <div class="status-item mb-4">
                                    <div class="d-flex">
                                        <div class="status-icon me-3">
                                            @php
                                                $statusColors = [
                                                    'On Port' => '#17a2b8',
                                                    'Pickedup' => '#ffc107',
                                                    'In Transit' => '#0d6efd',
                                                    'In Warehouse' => '#198754',
                                                    'Transferred' => '#6c757d',
                                                ];
                                                $color = $statusColors[$status->status] ?? '#0063f7';
                                            @endphp
                                            <div class="rounded-circle" style="width: 10px; height: 10px; background-color: {{ $color }};"></div>
                                            @if(!$loop->last)
                                                <div class="status-line"></div>
                                            @endif
                                        </div>
                                        <div class="status-content">
                                            <h6 class="mb-0 fw-medium small">{{ $status->status }}</h6>
                                            <p class="text-muted small mb-0">
                                                {{ $status->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="collapse" id="moreStatuses">
                                @foreach($statuses->skip(3) as $status)
                                    <div class="status-item mb-4">
                                        <div class="d-flex">
                                            <div class="status-icon me-3">
                                                @php
                                                    $color = $statusColors[$status->status] ?? '#0063f7';
                                                @endphp
                                                <div class="rounded-circle" style="width: 10px; height: 10px; background-color: {{ $color }};"></div>
                                                @if(!$loop->last)
                                                    <div class="status-line"></div>
                                                @endif
                                            </div>
                                            <div class="status-content">
                                                <h6 class="mb-0 fw-medium small">{{ $status->status }}</h6>
                                                <p class="text-muted small mb-0">
                                                    {{ $status->created_at->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div class="card border-0 shadow-sm mb-8">
        <div class="card-header bg-white p-0">
            <ul class="nav nav-tabs" id="containerTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="shipments-tab" data-bs-toggle="tab" data-bs-target="#shipments" type="button" role="tab" aria-controls="shipments" aria-selected="true">
                        <i class="ri-truck-line me-8"></i> Shipments ({{ $shipments->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab" aria-controls="invoices" aria-selected="false">
                        <i class="ri-file-list-3-line me-8"></i> Invoices ({{ $invoices->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab" aria-controls="payments" aria-selected="false">
                        <i class="ri-bank-card-line me-8"></i> Payments ({{ $payments->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="transfers-tab" data-bs-toggle="tab" data-bs-target="#transfers" type="button" role="tab" aria-controls="transfers" aria-selected="false">
                        <i class="ri-exchange-line me-8"></i> Transfers ({{ $transfers->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                        <i class="ri-file-line me-8"></i> Documents ({{ $containerDocuments->count() + $shipmentDocuments->count() }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="containerTabContent">
                <!-- Shipments Tab -->
                <div class="tab-pane fade show active" id="shipments" role="tabpanel" aria-labelledby="shipments-tab">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <h5 class="card-title mb-0">Shipment List</h5>
                        @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['create_shipment']))
                            <div class="d-flex gap-4">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#add_shippments">
                                    <i class="ri-file-excel-line me-8"></i> Import Stuffing Report
                                </button>
                                <a href="{{ route('shippments.create') }}" class="btn btn-sm btn-primary">
                                    <i class="ri-add-line me-8"></i> Add Shipment
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="shipmentsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Agent</th>
                                    <th>Description</th>
                                    <th>Mark</th>
                                    <th>Weight</th>
                                    <th>Packages</th>
                                    <th class="text-end actions-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipments as $shipment)
                                    <tr>
                                        <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('customers.show', $shipment->customer_id) }}" class="text-decoration-none">
                                                {{ $shipment->customer->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('agents.show', $shipment->agent_id) }}" class="text-decoration-none">
                                                {{ $shipment->agent->name }}
                                            </a>
                                        </td>
                                        <td>{{ $shipment->description }}</td>
                                        <td>{{ $shipment->mark }}</td>
                                        <td>{{ $shipment->weight }} {{ $shipment->unit_of_measure }}</td>
                                        <td>{{ $shipment->number_of_packages }}</td>
                                        <td class="text-end actions-column">
                                            <a href="{{ route('shippments.show', $shipment->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <h5 class="card-title mb-0">Invoice List</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="invoicesTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Shipment</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="text-decoration-none fw-bold">
                                                {{ $invoice->invoice_number ?? 'INV-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->shippment->customer->name ?? 'N/A' }}</td>
                                        <td>{{ $invoice->shippment->description ?? 'N/A' }}</td>
                                        <td>{{ number_format($invoice->total_amount) }} UGX</td>
                                        <td>
                                            @if($invoice->payment_status == 'paid')
                                                <span class="badge bg-success-subtle text-success">Paid</span>
                                            @elseif($invoice->payment_status == 'partially paid')
                                                <span class="badge bg-warning-subtle text-warning">Partially Paid</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payments Tab -->
                <div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <h5 class="card-title mb-0">Payment List</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Receipt #</th>
                                    <th>Invoice #</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Reference</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('payments.show', $payment->id) }}" class="text-decoration-none fw-bold">
                                                {{ $payment->receipt_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="text-decoration-none">
                                                {{ $payment->invoice->invoice_number }}
                                            </a>
                                        </td>
                                        <td>{{ number_format($payment->amount) }} UGX</td>
                                        <td>{{ $payment->mode_of_payment }}</td>
                                        <td>{{ $payment->reference_number }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Transfers Tab -->
                <div class="tab-pane fade" id="transfers" role="tabpanel" aria-labelledby="transfers-tab">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <h5 class="card-title mb-0">Transfer List</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="transfersTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Shipment</th>
                                    <th>Transferred To</th>
                                    <th>Transferred By</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfers as $transfer)
                                    <tr>
                                        <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('shippments.show', $transfer->shippment_id) }}" class="text-decoration-none">
                                                {{ $transfer->shippment->description }}
                                            </a>
                                        </td>
                                        <td>{{ $transfer->transfered_to }}</td>
                                        <td>{{ $transfer->user->name }}</td>
                                        <td>{{ $transfer->notes }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    <div class="d-flex justify-content-between align-items-center mb-8">
                        <h5 class="card-title mb-0">Document List</h5>
                        @if (Auth::user()->canDo(['add_shipment_documents']))
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#container_documents">
                                <i class="ri-file-add-line me-8"></i> Add Document
                            </button>
                        @endif
                    </div>

                    <!-- Container Documents -->
                    <h6 class="fw-bold mb-8">Container Documents</h6>
                    <div class="table-responsive mb-8">
                        <table class="table table-hover" id="containerDocsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Document Name</th>
                                    <th>Uploaded By</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($containerDocuments as $document)
                                    <tr>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                        <td>{{ $document->file_name }}</td>
                                        <td>{{ $document->user->name }}</td>
                                        <td class="text-end">
                                            <a href="{{ asset('documents/'.$document->file_url) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Shipment Documents -->
                    <h6 class="fw-bold mb-8">Shipment Documents</h6>
                    <div class="table-responsive">
                        <table class="table table-hover" id="shipmentDocsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Document Name</th>
                                    <th>Shipment</th>
                                    <th>Uploaded By</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipmentDocuments as $document)
                                    <tr>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                        <td>{{ $document->file_name }}</td>
                                        <td>
                                            <a href="{{ route('shippments.show', $document->shippment_id) }}" class="text-decoration-none">
                                                {{ $document->shippment->description }}
                                            </a>
                                        </td>
                                        <td>{{ $document->user->name }}</td>
                                        <td class="text-end">
                                            <a href="{{ asset('documents/'.$document->file_url) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('models.container_status')
@include('models.container_document')
@include('models.add_shippment')

<!-- Mark as Paid Modal -->
<div class="modal fade" id="mark_as_paid_modal" tabindex="-1" aria-labelledby="markAsPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAsPaidModalLabel">Mark Container as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="ri-information-line me-4"></i>
                    <span>Are you sure you want to mark this container as paid?</span>
                </div>
                <p>This will update the payment status from <strong>{{ $transit->payment_status ?: 'not paid' }}</strong> to <strong>paid</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-circle-line me-8"></i> Cancel
                </button>
                <form action="{{ route('containers.mark-as-paid', $transit->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="ri-checkbox-circle-line me-8"></i> Mark as Paid
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.data_tables')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all DataTables
        $('#shipmentsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search shipments...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ shipments",
            }
        });

        $('#invoicesTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search invoices...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ invoices",
            }
        });

        $('#paymentsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search payments...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ payments",
            }
        });

        $('#transfersTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search transfers...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ transfers",
            }
        });

        $('#containerDocsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search documents...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ documents",
            }
        });

        $('#shipmentDocsTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search documents...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ documents",
            }
        });

        // Initialize Financial Chart
        const totalInvoiced = {{ $totalInvoiced ?? 0 }};
        const totalPaid = {{ $totalPaid ?? 0 }};
        const freightValue = {{ is_numeric($transit->entry_number) ? intval($transit->entry_number) : 0 }};

        // Get invoice and payment data
        const invoices = @json($invoices->map(function($invoice) {
            try {
                return [
                    'x' => $invoice->created_at->format('Y-m-d'),
                    'y' => $invoice->total_amount ?? 0
                ];
            } catch (\Exception $e) {
                return [
                    'x' => date('Y-m-d'),
                    'y' => 0
                ];
            }
        }));

        const payments = @json($payments->map(function($payment) {
            try {
                return [
                    'x' => $payment->created_at->format('Y-m-d'),
                    'y' => $payment->amount ?? 0
                ];
            } catch (\Exception $e) {
                return [
                    'x' => date('Y-m-d'),
                    'y' => 0
                ];
            }
        }));

        // Sort data by date
        invoices.sort((a, b) => new Date(a.x) - new Date(b.x));
        payments.sort((a, b) => new Date(a.x) - new Date(b.x));

        // Calculate cumulative totals
        let invoiceRunningTotal = 0;
        const invoiceCumulative = invoices.map(item => {
            invoiceRunningTotal += parseInt(item.y);
            return {
                x: item.x,
                y: invoiceRunningTotal
            };
        });

        let paymentRunningTotal = 0;
        const paymentCumulative = payments.map(item => {
            paymentRunningTotal += parseInt(item.y);
            return {
                x: item.x,
                y: paymentRunningTotal
            };
        });

        // Create freight value reference line
        // Use the earliest and latest dates from invoices or payments for the freight line
        let allDates = [...invoices.map(i => new Date(i.x)), ...payments.map(p => new Date(p.x))];
        allDates.sort((a, b) => a - b);

        const freightLine = [];
        if (allDates.length > 0) {
            // Add freight value as a horizontal line across the chart
            const earliestDate = allDates[0];
            const latestDate = allDates[allDates.length - 1];

            // If we have dates, create a horizontal line
            freightLine.push({
                x: earliestDate.toISOString().split('T')[0],
                y: freightValue
            });

            freightLine.push({
                x: latestDate.toISOString().split('T')[0],
                y: freightValue
            });
        } else {
            // If no dates available, use container creation date
            const containerDate = '{{ $transit->created_at->format("Y-m-d") }}';
            freightLine.push({
                x: containerDate,
                y: freightValue
            });

            // Add 30 days to create a line
            const endDate = new Date(containerDate);
            endDate.setDate(endDate.getDate() + 30);
            freightLine.push({
                x: endDate.toISOString().split('T')[0],
                y: freightValue
            });
        }

        // Create chart options
        const chartOptions = {
            series: [
                {
                    name: 'Invoiced',
                    data: invoiceCumulative,
                    color: '#198754'
                },
                {
                    name: 'Paid',
                    data: paymentCumulative,
                    color: '#0d6efd'
                },
                {
                    name: 'Freight Value',
                    data: freightLine,
                    color: '#dc3545'
                }
            ],
            chart: {
                height: 250,
                type: 'area',
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit',
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 2,
                    blur: 4,
                    opacity: 0.1
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            fill: {
                type: ['gradient', 'gradient', 'solid'],
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    inverseColors: false,
                    opacityFrom: 0.8,
                    opacityTo: 0.2,
                },
                opacity: [0.8, 0.8, 0.1]
            },
            stroke: {
                width: [2, 2, 3],
                curve: ['smooth', 'smooth', 'straight'],
                lineCap: 'round',
                dashArray: [0, 0, 5]
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 3,
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 10
                },
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#198754', '#0d6efd'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                },
                discrete: [{
                    seriesIndex: 0,
                    dataPointIndex: invoiceCumulative.length - 1,
                    size: 6,
                    strokeColor: '#198754',
                    fillColor: '#fff'
                }, {
                    seriesIndex: 1,
                    dataPointIndex: paymentCumulative.length - 1,
                    size: 6,
                    strokeColor: '#0d6efd',
                    fillColor: '#fff'
                }]
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit',
                        fontWeight: 400,
                        cssClass: 'text-muted'
                    },
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: "MMM 'yy",
                        day: 'dd MMM',
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    show: true,
                    width: 1,
                    position: 'back',
                    opacity: 0.9,
                    stroke: {
                        color: '#b6b6b6',
                        width: 1,
                        dashArray: 3,
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: 'inherit',
                        fontWeight: 400,
                        cssClass: 'text-muted'
                    },
                    formatter: function(val) {
                        return val.toLocaleString() + ' UGX';
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: 'light',
                style: {
                    fontSize: '12px',
                    fontFamily: 'inherit'
                },
                marker: {
                    show: true,
                },
                x: {
                    format: 'dd MMM yyyy',
                },
                y: {
                    formatter: function(y) {
                        if (typeof y !== 'undefined') {
                            return y.toLocaleString() + ' UGX';
                        }
                        return y;
                    }
                }
            },
            legend: {
                show: false
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    chart: {
                        height: 200
                    },
                    grid: {
                        padding: {
                            left: 5
                        }
                    }
                }
            }]
        };

        // Render the chart
        const chart = new ApexCharts(document.querySelector("#finance-chart"), chartOptions);
        chart.render();

        // Add CSS for status timeline
        const style = document.createElement('style');
        style.textContent = `
            .status-timeline {
                position: relative;
                max-height: 300px;
                overflow-y: auto;
                padding-right: 10px;
            }
            .status-line {
                position: absolute;
                top: 12px;
                left: 5px;
                width: 2px;
                height: calc(100% - 12px);
                background-color: #e9ecef;
            }
            .status-item {
                position: relative;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
