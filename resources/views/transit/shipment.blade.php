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
            <p class="text-muted small mb-0">Shipment details and related information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('shippments.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back to Shipments
            </a>
            @if(Auth::user()->canDo(['edit_shipment']))
            <a href="{{ route('shippments.edit', $shippment->id) }}" class="btn btn-outline-primary">
                <i class="ri-edit-line me-8"></i> Edit Shipment
            </a>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4">
                @if(Auth::user()->canDo(['add_shipment_documents']))
                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#shippment_documents">
                    <i class="ri-file-add-line me-8"></i> Add Documents
                </button>
                @endif

                @if(Auth::user()->canDo(['add_shipment_invoice']))
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#add_new_invoice">
                    <i class="ri-file-list-3-line me-8"></i> Add Invoice
                </button>
                @endif

                @if(Auth::user()->canDo(['add_shipment_transfers']))
                <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#make_transfer">
                    <i class="ri-exchange-line me-8"></i> Transfer
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Shipment Details Section -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-4 text-primary"></i>Shipment Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-8">
                        <div style="width: 48px; height: 48px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <i class="ri-box-3-line" style="color: #0d6efd; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $shippment->description }}</h5>
                            <p class="text-muted small mb-0">Created: {{ $shippment->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row g-4 mb-8">
                        <div class="col-12">
                            <div class="p-8 rounded" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 110, 253, 0.1));">
                                <p class="text-muted mb-1 small">Container</p>
                                <div class="d-flex align-items-center">
                                    <i class="ri-ship-2-line me-4 text-primary"></i>
                                    <p class="mb-0 fw-medium">{{ $shippment->transit->number }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Customer</p>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-3-line me-4 text-primary"></i>
                                    <div>
                                        <p class="mb-0 fw-medium">{{ $shippment->customer->name }}</p>
                                        <p class="mb-0 small text-muted">{{ $shippment->customer->phone_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Sales Agent</p>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-star-line me-4 text-primary"></i>
                                    <div>
                                        <p class="mb-0 fw-medium">{{ $shippment->agent->name }}</p>
                                        <p class="mb-0 small text-muted">{{ $shippment->agent->phone_number }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-4 text-primary">Shipment Information</h6>

                    <div class="mb-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">Shipper:</span>
                            <span class="fw-medium">{{ $shippment->shipper }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">Mark:</span>
                            <span class="fw-medium">{{ $shippment->mark }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">BL Packages:</span>
                            <span class="fw-medium">{{ $shippment->bl_packages }} {{ $shippment->unit_of_measure }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">No. Packages:</span>
                            <span class="fw-medium">{{ $shippment->number_of_packages }} {{ $shippment->unit_of_measure }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">Weight:</span>
                            <span class="fw-medium">{{ $shippment->weight }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">Volume:</span>
                            <span class="fw-medium">{{ $shippment->volume }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">Type:</span>
                            <span class="fw-medium">{{ $shippment->type }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted small">CBM:</span>
                            <span class="fw-medium">{{ $shippment->cbm }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Recorded By:</span>
                            <span class="fw-medium">{{ $shippment->transit->user->name }}</span>
                        </div>
                    </div>

                    @if(Auth::user()->canDo(['view_shipment_documents']) && $documents->count() > 0)
                    <h6 class="fw-bold mb-4 text-primary">Documents</h6>
                    <div class="document-list">
                        @foreach($documents as $document)
                            @php
                                $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                $iconClass = 'ri-file-text-line';
                                $colorClass = 'text-primary';

                                if (in_array(strtolower($extension), ['pdf'])) {
                                    $iconClass = 'ri-file-pdf-line';
                                    $colorClass = 'text-danger';
                                } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                    $iconClass = 'ri-file-word-line';
                                    $colorClass = 'text-primary';
                                } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                    $iconClass = 'ri-file-excel-line';
                                    $colorClass = 'text-success';
                                } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $iconClass = 'ri-image-line';
                                    $colorClass = 'text-info';
                                }
                            @endphp
                            <a href="{{ asset('documents/'.$document->file_url) }}" class="document-item d-flex align-items-center p-8 mb-4 rounded bg-light text-decoration-none" target="_blank">
                                <div class="document-icon me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1);">
                                    <i class="{{ $iconClass }} {{ $colorClass }}" style="font-size: 20px;"></i>
                                </div>
                                <div class="document-info flex-grow-1">
                                    <h6 class="mb-1 text-dark">{{ $document->file_name }}</h6>
                                    <p class="mb-0 text-muted small">Added {{ $document->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="document-action">
                                    <i class="ri-download-line text-primary"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabbed Content Section -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white p-0">
                    <ul class="nav nav-tabs" id="shipmentTab" role="tablist">
                        @if(Auth::user()->canDo(['view_shipment_invoice']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices" type="button" role="tab" aria-controls="invoices" aria-selected="true">
                                <i class="ri-file-list-3-line me-8"></i> Invoices ({{ $invoices->count() }})
                            </button>
                        </li>
                        @endif
                        @if(Auth::user()->canDo(['view_shipment_transfers']))
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !Auth::user()->canDo(['view_shipment_invoice']) ? 'active' : '' }}" id="transfers-tab" data-bs-toggle="tab" data-bs-target="#transfers" type="button" role="tab" aria-controls="transfers" aria-selected="{{ !Auth::user()->canDo(['view_shipment_invoice']) ? 'true' : 'false' }}">
                                <i class="ri-exchange-line me-8"></i> Transfers ({{ $tranfers->count() }})
                            </button>
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="shipmentTabContent">
                        <!-- Invoices Tab -->
                        @if(Auth::user()->canDo(['view_shipment_invoice']))
                        <div class="tab-pane fade show active" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                            <div class="d-flex justify-content-between align-items-center mb-8">
                                <h5 class="card-title mb-0">Invoice List</h5>
                                @if(Auth::user()->canDo(['add_shipment_invoice']))
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_new_invoice">
                                    <i class="ri-add-line me-8"></i> Add Invoice
                                </button>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="invoicesTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->created_at->format('M d, Y') }}</td>
                                            <td>{{ number_format($invoice->totalAmount($invoice->id)) }} UGX</td>
                                            <td>{{ number_format($invoice->balance($invoice->id)) }} UGX</td>
                                            <td>
                                                @php
                                                    $balance = $invoice->balance($invoice->id);
                                                    $total = $invoice->totalAmount($invoice->id);
                                                @endphp
                                                @if($balance == 0)
                                                    <span class="badge bg-success-subtle text-success">Paid</span>
                                                @elseif($balance < $total)
                                                    <span class="badge bg-warning-subtle text-warning">Partial</span>
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
                        @endif

                        <!-- Transfers Tab -->
                        @if(Auth::user()->canDo(['view_shipment_transfers']))
                        <div class="tab-pane fade {{ !Auth::user()->canDo(['view_shipment_invoice']) ? 'show active' : '' }}" id="transfers" role="tabpanel" aria-labelledby="transfers-tab">
                            <div class="d-flex justify-content-between align-items-center mb-8">
                                <h5 class="card-title mb-0">Transfer List</h5>
                                @if(Auth::user()->canDo(['add_shipment_transfers']))
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#make_transfer">
                                    <i class="ri-add-line me-8"></i> Add Transfer
                                </button>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="transfersTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Destination</th>
                                            <th>Reference</th>
                                            <th>By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tranfers as $tranfer)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($tranfer->date_transfered)->format('M d, Y') }}</td>
                                            <td>{{ $tranfer->product_name }}</td>
                                            <td>{{ $tranfer->quantity }}</td>
                                            <td>{{ $tranfer->destination }}</td>
                                            <td>{{ $tranfer->reference }}</td>
                                            <td>{{ $tranfer->user->name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('models.add_invoice')
@include('models.shippment_documents')
@include('models.tranfers')

@endsection

@include('layouts.data_tables')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables
        $('#invoicesTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search invoices...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ invoices",
                emptyTable: 'No invoices found'
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
                emptyTable: 'No transfers found'
            }
        });

        // Add CSS for enhanced UI elements
        const style = document.createElement('style');
        style.textContent = `
            /* Document List Styles */
            .document-item {
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }
            .document-item:hover {
                transform: translateY(-2px);
                border-color: #dee2e6;
                box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            }

            /* DataTables customization */
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 1rem;
            }
            .dataTables_wrapper .dataTables_length {
                margin-bottom: 1rem;
            }
            .dataTables_wrapper .dataTables_info {
                margin-top: 1rem;
            }
            .dataTables_wrapper .dataTables_paginate {
                margin-top: 1rem;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush