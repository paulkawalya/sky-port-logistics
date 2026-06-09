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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">
                <a href="{{ route('requisitions.index') }}" class="text-decoration-none">All Requisitions</a>
                <i class="ri-arrow-right-s-line"></i>
                <a href="{{ route('requisitions.index') }}?user={{ $requisition->user_id }}" class="text-decoration-none">{{ $requisition->user->name }}'s Requisitions</a>
            </p>
        </div>
        <div class="d-flex gap-4" id="exclude">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="requisitionActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-2-fill me-8"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="requisitionActionsDropdown">
                    <li>
                        <button type="button" class="dropdown-item" onclick="printDirectly()">
                            <i class="ri-printer-line me-8 text-primary"></i> Print Requisition
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#shareOptionsModal">
                            <i class="ri-share-line me-8 text-success"></i> Share Requisition
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>

                    @if(Auth::user()->canDo(['add_documents_to_requisition']))
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#shippment_documents">
                            <i class="ri-file-add-line me-8 text-info"></i> Add Documents
                        </button>
                    </li>
                    @endif

                    @if(!$requisition->coverted($requisition->id))
                        @if(Auth::user()->canDo(['add_requisition']) && $requisition->user_id == Auth::id())
                        <li>
                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_requisition_item">
                                <i class="ri-add-circle-line me-8 text-primary"></i> Add Item
                            </button>
                        </li>
                        @endif

                        @if(Auth::user()->canDo(['approve_requisition']))
                        <li>
                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_requisition_status">
                                <i class="ri-checkbox-circle-line me-8 text-success"></i> Approve Requisition
                            </button>
                        </li>
                        @endif

                        @if(Auth::user()->canDo(['add_payment_to_requisition']))
                        <li>
                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_payment">
                                <i class="ri-money-dollar-circle-line me-8 text-warning"></i> Add Payment
                            </button>
                        </li>
                        @endif

                        @if($requisition->user_id == Auth::id() && count($approvers) > 0)
                        <li>
                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#notify_approvers">
                                <i class="ri-notification-line me-8 text-danger"></i> Notify Approvers
                            </button>
                        </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Requisition Content -->
    <div class="row">
        <!-- Requisition Details Card -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-file-list-3-line me-4 text-primary"></i>Requisition Details
                        </h5>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                            {{ $requisition->currency ?? 'UGX' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-16">
                    <!-- Requisition Header -->
                    <div class="text-center mb-8">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/skyport.png'))) }}" alt="SKY PORT LOGISTICS LTD" style="width: auto; max-width: 360px; max-height: 180px; margin: 0 auto 16px; display: block;">
                        <h4 class="fw-bold mb-0">REQUISITION</h4>
                        <div class="border-bottom border-2 border-primary w-25 mx-auto mt-2 mb-4"></div>
                    </div>

                    <!-- Requisition Info Section -->
                    <div class="row g-4 mb-8">
                        <!-- Requester Info -->
                        <div class="col-md-4">
                            <div class="p-8 rounded bg-light">
                                <h6 class="fw-bold mb-4 text-uppercase">Requester</h6>
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-box me-4 flex-shrink-0" style="width: 40px; height: 40px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1); display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-user-3-line text-primary" style="font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium">{{ $requisition->user->name }}</p>
                                        <p class="mb-0 small text-muted">{{ $requisition->user->phone_number }}</p>
                                        <p class="mb-0 small text-muted">{{ $requisition->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Requisition Info -->
                        <div class="col-md-4">
                            <div class="p-8 rounded bg-light">
                                <h6 class="fw-bold mb-4 text-uppercase">Requisition Information</h6>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Requisition No:</span>
                                    <span class="fw-medium">{{str_pad($requisition->id, 4, '0', STR_PAD_LEFT)}}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Date:</span>
                                    <span class="fw-medium">{{Carbon\Carbon::parse($requisition->date_requisitioned)->format("d M Y")}}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Status:</span>
                                    <span class="badge {{ $requisition->status($requisition->id) == 'approved' ? 'bg-success' : ($requisition->status($requisition->id) == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($requisition->status($requisition->id)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Info -->
                        <div class="col-md-4">
                            <div class="p-8 rounded bg-light">
                                <h6 class="fw-bold mb-4 text-uppercase">Financial Details</h6>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Total Amount:</span>
                                    <span class="fw-medium">{{ number_format($requisition->calculated_amount ?? $requisition->amountCached($requisition->id)) }} {{ $requisition->currency ?? 'UGX' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Paid Amount:</span>
                                    <span class="fw-medium text-success">{{ number_format($requisition->calculated_paid ?? $requisition->paidCached($requisition->id)) }} {{ $requisition->currency ?? 'UGX' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Balance:</span>
                                    <span class="fw-medium {{ ($requisition->calculated_balance ?? $requisition->balanceCached($requisition->id)) > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($requisition->calculated_balance ?? $requisition->balanceCached($requisition->id)) }} {{ $requisition->currency ?? 'UGX' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Requisition Items Section -->
                    <div class="mb-8">
                        <h6 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="ri-list-check-3 me-4 text-primary"></i>Requisition Items
                            @if(Auth::id() == $requisition->user_id && !$requisition->coverted($requisition->id))
                            <button type="button" class="btn btn-sm btn-outline-primary ms-auto" data-toggle="modal" data-target="#add_requisition_item">
                                <i class="ri-add-line me-8"></i> Add Item
                            </button>
                            @endif
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="25%">Item</th>
                                        <th scope="col" width="10%">Quantity</th>
                                        <th scope="col" width="15%">Unit Price</th>
                                        <th scope="col" width="15%">Amount</th>
                                        <th scope="col" width="20%">Reason</th>
                                        @if(Auth::id() == $requisition->user_id && !$requisition->coverted($requisition->id))
                                        <th scope="col" width="10%" class="text-end">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requisition->details as $row => $detail)
                                    <tr>
                                        <td>{{$row+1}}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium">{{ $detail->particular }}</span>
                                                @if(!empty($detail->expense_category_id))
                                                <span class="badge bg-light text-dark small">{{ $detail->category->name }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->estimated_cost) }}</td>
                                        <td class="fw-semibold">{{ number_format($detail->quantity * $detail->estimated_cost) }}</td>
                                        <td><span class="text-muted small">{{ $detail->reason }}</span></td>
                                        @if(Auth::id() == $requisition->user_id && !$requisition->coverted($requisition->id))
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-icon" data-toggle="modal" data-target="#edit_requisition_item{{$detail->id}}">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                    @include("models.edit_requisition_detail")
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Amount in Words Section -->
                    <div class="mb-8">
                        <div class="p-8 rounded bg-light">
                            <h6 class="fw-bold mb-4 text-uppercase">Amount in Words</h6>
                            <p class="mb-0 text-uppercase text-danger fw-medium">
                                {{ \App\Models\Invoice::numberToWords(round($requisition->calculated_amount ?? $requisition->amountCached($requisition->id))) }} {{ $requisition->currency ?? 'UGX' }} ONLY
                            </p>
                        </div>
                    </div>

                    <!-- Approvals Section -->
                    <div class="mb-8">
                        <h6 class="fw-bold mb-4">
                            <i class="ri-user-follow-line me-4 text-success"></i>Approvals
                            @if(Auth::user()->canDo(['approve_requisition']))
                            <button type="button" class="btn btn-sm btn-outline-success ms-auto" data-toggle="modal" data-target="#add_requisition_status">
                                <i class="ri-checkbox-circle-line me-8"></i> Add Approval
                            </button>
                            @endif
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Approver</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($requisition->approvals->count() > 0)
                                        @foreach($requisition->approvals as $approval)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-box me-4 flex-shrink-0" style="width: 32px; height: 32px; border-radius: 50%; background-color: rgba(13, 110, 253, 0.1); display: flex; align-items: center; justify-content: center;">
                                                        <i class="ri-user-line text-primary"></i>
                                                    </div>
                                                    <span>{{ $approval->user->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-white badge {{ $approval->status == 'approved' ? 'bg-success' : ($approval->status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ ucfirst($approval->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $approval->remarks }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ri-information-line me-4"></i>No approvals recorded yet
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- This section is intentionally left empty as we're moving Documents to the right column -->
                    <div class="mb-8"></div>
                </div>
            </div>
        </div>

        <!-- Requisition Summary Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px; z-index: 100;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-calculator-line me-4 text-primary"></i>Requisition Summary
                    </h5>
                </div>
                <div class="card-body p-16">
                    <!-- Payment Status -->
                    <div class="text-center mb-8">
                        @php
                            $statusClass = 'bg-danger-subtle text-danger';
                            $statusText = 'Pending Payment';
                            $statusIcon = 'ri-error-warning-line';

                            if ($requisition->balance($requisition->id) == 0 && $requisition->amount($requisition->id) > 0) {
                                $statusClass = 'bg-success-subtle text-success';
                                $statusText = 'Fully Paid';
                                $statusIcon = 'ri-checkbox-circle-line';
                            } elseif ($requisition->balance($requisition->id) < $requisition->amount($requisition->id) && $requisition->amount($requisition->id) > 0) {
                                $statusClass = 'bg-warning-subtle text-warning';
                                $statusText = 'Partially Paid';
                                $statusIcon = 'ri-time-line';
                            }
                        @endphp
                        <div class="mb-4">
                            <span class="badge {{ $statusClass }} px-4 py-2 fs-6">
                                <i class="{{ $statusIcon }} me-4"></i>{{ $statusText }}
                            </span>
                        </div>

                        @if($requisition->balance($requisition->id) > 0 && Auth::user()->canDo(['add_payment_to_requisition']))
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_payment">
                            <i class="ri-bank-card-line me-8"></i> Make Payment
                        </button>
                        @endif
                    </div>

                    <!-- Payments Section -->
                    @if($payments->count() > 0)
                    <div class="mb-8">
                        <h6 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="ri-bank-card-line me-4 text-success"></i>Payment History
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                        <td class="fw-semibold">{{ number_format($payment->amount) }}</td>
                                        <td>
                                            @php
                                                $modeIcons = [
                                                    'Cash' => 'ri-money-dollar-circle-line text-success',
                                                    'Bank' => 'ri-bank-line text-primary',
                                                    'Mobile Money' => 'ri-smartphone-line text-warning',
                                                    'Digital' => 'ri-global-line text-info'
                                                ];
                                                $icon = $modeIcons[$payment->mode_of_payment] ?? 'ri-money-dollar-circle-line';
                                            @endphp
                                            <i class="{{ $icon }} me-2"></i>
                                            <span class="small">{{ $payment->mode_of_payment }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Documents Section -->
                    <div class="mb-8">
                        <h6 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="ri-file-list-line me-4 text-info"></i>Documents
                            @if(Auth::user()->canDo(['add_documents_to_requisition']))
                            <button type="button" class="btn btn-sm btn-outline-info ms-auto" data-toggle="modal" data-target="#shippment_documents">
                                <i class="ri-file-add-line me-8"></i> Add
                            </button>
                            @endif
                        </h6>
                        <div class="mb-4">
                            @if($documents->count() > 0)
                                @foreach($documents as $document)
                                <div class="p-8 rounded border d-flex align-items-center mb-4">
                                    <div class="icon-box me-4 flex-shrink-0" style="width: 32px; height: 32px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1); display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-file-line text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 small text-muted">{{ Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}</p>
                                        <a href="{{ asset('documents/'.$document->file_url) }}" class="text-decoration-none fw-medium" target="_blank">
                                            {{ $document->file_name }}
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="p-16 rounded border text-center">
                                    <div class="text-muted">
                                        <i class="ri-file-list-3-line fs-4 mb-4 d-block"></i>
                                        <p class="mb-0">No documents attached</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-4 text-uppercase">Quick Actions</h6>
                        <div class="d-grid gap-4">
                            @if(Auth::user()->canDo(['approve_requisition']) && $requisition->status($requisition->id) != 'approved')
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_requisition_status">
                                <i class="ri-checkbox-circle-line me-8"></i> Approve Requisition
                            </button>
                            @endif

                            <button type="button" class="btn btn-outline-primary" onclick="printDirectly()">
                                <i class="ri-printer-line me-8"></i> Print Requisition
                            </button>
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#shareOptionsModal">
                                <i class="ri-share-line me-8"></i> Share Requisition
                            </button>
                            @if($requisition->user_id == Auth::id() && count($approvers) > 0)
                            <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#notify_approvers">
                                <i class="ri-notification-line me-8"></i> Notify Approvers
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="mt-8 pt-4 border-top">
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>Printed by:</span>
                            <span>{{Auth::user()->name}}</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>Printing time:</span>
                            <span>{{now()->format('M d, Y H:i')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="add_payment" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="ri-bank-card-line me-4 text-success"></i>Record Payment
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('requisition_payments.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0"><i class="ri-information-line me-4 text-primary"></i>Payment Details</h6>
                                </div>
                                <div class="card-body p-16">
                                    <!-- Item Selection -->
                                    <div class="mb-4">
                                        <label for="requisition_detail_id" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-list-check-3"></i>
                                            <span>Select Item <span class="text-danger">*</span></span>
                                        </label>
                                        <select name="requisition_detail_id" id="requisition_detail_id" class="form-select" required>
                                            <option value="">Select an item</option>
                                            @foreach($requisition->details as $detail)
                                                <option value="{{ $detail->id }}">{{ $detail->particular }} ({{  empty($detail->expense_category_id) ? "": $detail->category->name }}) - {{number_format($detail->balance($detail->id))}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Select the item you're making payment for</div>
                                    </div>

                                    <!-- Payment Date -->
                                    <div class="mb-4">
                                        <label for="payment_date" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-calendar-line"></i>
                                            <span>Payment Date <span class="text-danger">*</span></span>
                                        </label>
                                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                                    </div>

                                    <!-- Amount -->
                                    <div class="mb-4">
                                        <label for="amount_paid" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-money-dollar-circle-line"></i>
                                            <span>Amount ({{ $requisition->currency }}) <span class="text-danger">*</span></span>
                                        </label>
                                        <input type="number" name="amount_paid" id="amount_paid" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0"><i class="ri-bank-line me-4 text-primary"></i>Payment Method</h6>
                                </div>
                                <div class="card-body p-16">
                                    <!-- Payment Mode -->
                                    <div class="mb-4">
                                        <label for="mode_of_payment" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-bank-card-line"></i>
                                            <span>Payment Method <span class="text-danger">*</span></span>
                                        </label>
                                        <select name="mode_of_payment" id="mode_of_payment" class="form-select" required>
                                            <option value="">Select payment method</option>
                                            @foreach($mode_of_payments as $mode_of_payment)
                                                <option value="{{ $mode_of_payment }}">{{ $mode_of_payment }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Account -->
                                    <div class="mb-4">
                                        <label for="payment_account_id" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-bank-line"></i>
                                            <span>Account to Debit <span class="text-danger">*</span></span>
                                        </label>
                                        <select name="payment_account_id" id="payment_account_id" class="form-select" required>
                                            <option value="">Select account</option>
                                        </select>
                                    </div>

                                    <!-- Supplier -->
                                    <div class="mb-4">
                                        <label for="supplier_id" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-store-line"></i>
                                            <span>Expense Category</span>
                                        </label>
                                        <select name="supplier_id" id="supplier_id" class="form-select">
                                            <option value="">Select expense category</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name." ".$supplier->address }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label for="particular" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-text"></i>
                                            <span>Description</span>
                                        </label>
                                        <input type="text" name="particular" id="particular" class="form-control" placeholder="Enter payment description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end mt-8 gap-4">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success px-8">
                            <i class="ri-save-line me-8"></i> Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include("models.add_requisition_item")
@include("models.approvals")
@include("models.requisition_doc")
@include("models.notify_apprivers")

<!-- Share Options Modal -->
<div class="modal fade" id="shareOptionsModal" tabindex="-1" role="dialog" aria-labelledby="shareOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareOptionsModalLabel">
                    <i class="ri-share-line me-4 text-success"></i>Share Requisition
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mb-4">
                        <span class="badge bg-primary-subtle text-primary px-4 py-2 fs-6">
                            Requisition #{{ str_pad($requisition->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <p class="text-muted">Choose how you want to share this requisition</p>
                </div>

                <div class="alert alert-light border-start border-4 border-info">
                    <div class="d-flex">
                        <i class="ri-information-line fs-4 me-4 text-info"></i>
                        <p class="mb-0">The secure PDF link can be shared with anyone. No login required - recipients will get a direct PDF download.</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-16">
                                <div class="icon-box mx-auto mb-4" style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(25, 135, 84, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="ri-link text-success" style="font-size: 28px;"></i>
                                </div>
                                <h5 class="mb-4">Secure PDF Link</h5>
                                <p class="small text-muted mb-4">Generate a secure PDF download link</p>
                                <button type="button" onclick="copyShareLink()" class="btn btn-outline-success w-100">
                                    <i class="ri-clipboard-line me-8"></i>Copy Link
                                </button>
                                <div id="copyShareMessage" class="small text-success mt-2 d-none">
                                    <i class="ri-check-line me-4"></i>Link copied!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-16">
                                <div class="icon-box mx-auto mb-4" style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 28px;"></i>
                                </div>
                                <h5 class="mb-4">Download PDF</h5>
                                <p class="small text-muted mb-4">Save as PDF document</p>
                                <a href="{{ route('requisitions.public.pdf', $requisition->hashed_id) }}" class="btn btn-outline-danger w-100">
                                    <i class="ri-download-line me-8"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="ri-close-line me-8"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden iframe for printing -->
<iframe id="hiddenPrintFrame" style="position: fixed; right: -9999px; bottom: -9999px; width: 0; height: 0; border: 0;"></iframe>

@endsection

@section('styles')
<style>
    @media print {
        #exclude, a, nav, #hide_form, .hp-sidebar, header {
            display: none;
        }
    }

    /* Sticky summary card */
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky;
            top: 80px;
            z-index: 1000;
        }
    }

    /* Payment status badges */
    .badge.bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
        color: #198754 !important;
    }

    .badge.bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
    }

    .badge.bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
    }

    .badge.bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
        color: #0d6efd !important;
    }

    .badge.bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.1) !important;
        color: #6c757d !important;
    }

    /* Table styles */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    /* Icon button styles */
    .btn-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0 !important;
    }

    .btn-icon i {
        font-size: 1rem;
        line-height: 1;
    }

    /* Label with icon styles */
    .label-with-icon {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .label-with-icon i {
        color: #6c757d;
        font-size: 1.1rem;
    }

    /* Chosen container styles */
    .chosen-container {
        width: 100% !important;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize chosen selects
        $("#requisition_detail_id, #supplier_id, #mode_of_payment, #payment_account_id").chosen({
            width: "100%",
            no_results_text: "No results match",
            allow_single_deselect: true
        });

        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Handle mode of payment change
        $('#mode_of_payment').on('change', function(e) {
            var mode_of_payment = e.target.value;
            var currency = "{!! $requisition->currency !!}";

            $.get('/get_accounts/' + mode_of_payment + '/' + currency, function(data) {
                $("#payment_account_id").chosen('destroy');
                $("#payment_account_id").empty();
                $('#payment_account_id').append("<option value=''>Select account</option>");

                $.each(data, function(index, account) {
                    $('#payment_account_id').append("<option value=" + account.id + ">" + account.name + " " + account.account_number + " " + account.transaction_currency + "</option>");
                });

                // Reinitialize chosen
                $("#payment_account_id").chosen({
                    width: "100%",
                    no_results_text: "No results match",
                    allow_single_deselect: true
                });
            });
        });

        // Handle requisition detail change
        $('#requisition_detail_id').on('change', function(e) {
            var detail_id = e.target.value;

            if (detail_id) {
                $.get('/requsition_amount_detail/' + detail_id + '/', function(data) {
                    $("#amount_paid").val(data);
                });
            }
        });
    });

    // Print functionality
    function printDirectly() {
        // Use existing hidden iframe
        let printFrame = document.getElementById('hiddenPrintFrame');

        // Load the print page into the iframe
        printFrame.src = '{{ route("requisitions.secure.print", $requisition->hashed_id) }}?print=true';

        // Wait for the iframe to load, then print it
        printFrame.onload = function() {
            setTimeout(function() {
                try {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                } catch (e) {
                    console.error('Printing failed:', e);
                    // Fallback to opening in a new tab
                    window.open('{{ route("requisitions.secure.print", $requisition->hashed_id) }}?print=true', '_blank');
                }
            }, 500);
        };
    }

    // Copy share link functionality
    function copyShareLink() {
        const secureUrl = '{{ route("requisitions.public.pdf", $requisition->hashed_id) }}';

        // Try to use the modern Clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(secureUrl)
                .then(() => {
                    // Show success message
                    const copyMessage = document.getElementById("copyShareMessage");
                    copyMessage.classList.remove("d-none");

                    // Hide message after 3 seconds
                    setTimeout(function() {
                        copyMessage.classList.add("d-none");
                    }, 3000);
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                    // Fallback to the old method
                    fallbackCopyTextToClipboard(secureUrl);
                });
        } else {
            // Fallback for browsers that don't support the Clipboard API
            fallbackCopyTextToClipboard(secureUrl);
        }
    }

    // Fallback copy method
    function fallbackCopyTextToClipboard(text) {
        // Create a temporary input element
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Show success message
        const copyMessage = document.getElementById("copyShareMessage");
        copyMessage.classList.remove("d-none");

        // Hide message after 3 seconds
        setTimeout(function() {
            copyMessage.classList.add("d-none");
        }, 3000);
    }
</script>
@endpush


