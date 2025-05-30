@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Vendors/Suppliers</h4>
            <p class="text-muted small mb-0">Manage vendors and suppliers for your business expenses</p>
        </div>
        @if(Auth::user()->canDo(['create_suppliers']))
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Add Supplier
        </a>
        @endif
    </div>

    <!-- Supplier Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Suppliers Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-group-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($suppliers) }}</h5>
                            <p class="text-muted small mb-0">Total Suppliers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers with Address Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-map-pin-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $suppliers->filter(function($supplier) { return !empty($supplier->address); })->count() }}</h5>
                            <p class="text-muted small mb-0">With Address</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Added Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-time-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $suppliers->sortByDesc('created_at')->take(5)->count() }}</h5>
                            <p class="text-muted small mb-0">Recently Added</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-group-line me-4 text-primary"></i>Suppliers List
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
        <div class="card-body p-16">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabledata">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Supplier Name</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Added On</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{ str_pad($supplier->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $supplier->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line text-muted me-4"></i>
                                    <span>{{ $supplier->phone_number }}</span>
                                </div>
                            </td>
                            <td>
                                @if($supplier->address)
                                <div class="d-flex align-items-center">
                                    <i class="ri-map-pin-line text-muted me-4"></i>
                                    <span class="text-truncate" style="max-width: 200px;" title="{{ $supplier->address }}">
                                        {{ $supplier->address }}
                                    </span>
                                </div>
                                @else
                                <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ \Carbon\Carbon::parse($supplier->created_at)->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if(Auth::user()->canDo(['edit_suppliers']))
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Supplier">
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
    });
</script>
@endpush