@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage and track all shipments in the system</p>
        </div>
        @if(Auth::user()->canDo(['create_shipment']))
        <a href="{{ route('shippments.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-8"></i> Create Shipment
        </a>
        @endif
    </div>

    <!-- Shipments Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Shipments Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-box-3-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $shippments->total() }}</h5>
                            <p class="text-muted small mb-0">Total Shipments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month's Shipments -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-calendar-check-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $shippments->where('created_at', '>=', now()->startOfMonth())->count() }}</h5>
                            <p class="text-muted small mb-0">This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unique Customers -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(255, 193, 7, 0.1);">
                            <i class="ri-user-3-line text-warning"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $shippments->unique('customer_id')->count() }}</h5>
                            <p class="text-muted small mb-0">Unique Customers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unique Agents -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-team-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $shippments->unique('agent_id')->count() }}</h5>
                            <p class="text-muted small mb-0">Unique Agents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipments Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-ship-line me-4 text-primary"></i>Shipments List
                </h5>
                <div class="d-flex gap-4">
                    <button class="btn btn-sm btn-outline-primary" id="refresh-table">
                        <i class="ri-refresh-line"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-download-line"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                            <li><a class="dropdown-item" href="#" id="export-excel"><i class="ri-file-excel-line me-4 text-success"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#" id="export-pdf"><i class="ri-file-pdf-line me-4 text-danger"></i>PDF</a></li>
                            <li><a class="dropdown-item" href="#" id="export-print"><i class="ri-printer-line me-4 text-dark"></i>Print</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabledata">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Agent</th>
                            <th>Description</th>
                            <th>Mark</th>
                            <th>Shipper</th>
                            <th style="width: 120px;" class="actions-column">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shippments as $shippment)
                        <tr>
                            <td>{{ $shippment->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $shippment->customer->name }}</span>
                                    <span class="text-muted small"><i class="ri-phone-line me-8"></i>{{ $shippment->customer->phone_number }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $shippment->agent->name }}</span>
                                    <span class="text-muted small"><i class="ri-phone-line me-8"></i>{{ $shippment->agent->phone_number }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $shippment->description }}">
                                    {{ $shippment->description }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $shippment->mark }}</span>
                            </td>
                            <td>{{ $shippment->shipper }}</td>
                            <td class="actions-column">
                                <div class="d-flex gap-4">
                                    <a href="{{ route('shippments.show', $shippment->id) }}" class="btn btn-sm btn-primary">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    @if(Auth::user()->canDo(['edit_shipment']))
                                    <a href="{{ route('shippments.edit', $shippment->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.data_tables')

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Refresh button functionality
            document.getElementById('refresh-table').addEventListener('click', function() {
                const btn = this;
                const originalHtml = btn.innerHTML;

                // Show loading state
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                btn.disabled = true;

                // Refresh the page
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            });

            // Export functionality
            document.getElementById('export-excel').addEventListener('click', function(e) {
                e.preventDefault();
                $('.buttons-excel:first').click();
            });

            document.getElementById('export-pdf').addEventListener('click', function(e) {
                e.preventDefault();
                $('.buttons-pdf:first').click();
            });

            document.getElementById('export-print').addEventListener('click', function(e) {
                e.preventDefault();
                $('.buttons-print:first').click();
            });

            // Add print styles
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    .container-fluid { padding: 0 !important; }
                    .card { border: none !important; box-shadow: none !important; }
                    .card-header, .btn, .dropdown, .card-footer, .pagination { display: none !important; }
                    .table { width: 100% !important; }
                    .table th, .table td { padding: 8px !important; }
                }
                .dt-buttons { display: none !important; }
            `;
            document.head.appendChild(style);
        });
    </script>
@endpush
