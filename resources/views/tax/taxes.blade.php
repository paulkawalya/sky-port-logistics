@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage tax rates and amounts applied to invoices and quotations</p>
        </div>
        @if(Auth::user()->canDo(['create_taxes']))
        <a href="{{ route('taxes.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Add Tax
        </a>
        @endif
    </div>

    <!-- Tax Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Taxes Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-percent-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($taxes) }}</h5>
                            <p class="text-muted small mb-0">Total Tax Types</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- UGX Taxes Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-money-dollar-circle-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $taxes->where('currency', 'UGX')->count() }}</h5>
                            <p class="text-muted small mb-0">UGX Taxes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- USD Taxes Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-exchange-dollar-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $taxes->where('currency', 'USD')->count() }}</h5>
                            <p class="text-muted small mb-0">USD Taxes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Taxes Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-percent-line me-4 text-primary"></i>Tax List
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
                            <th>Tax Name</th>
                            <th>Percentage</th>
                            <th>Fixed Amount</th>
                            <th>Currency</th>
                            <th>Usage</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxes as $tax)
                        <tr>
                            <td>{{ str_pad($tax->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="fw-semibold">{{ $tax->tax_name }}</span>
                            </td>
                            <td>
                                @if($tax->percentage_tax)
                                <span class="badge bg-light text-dark">
                                    <i class="ri-percent-line me-1"></i> {{ number_format($tax->percentage_tax, 2) }}%
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($tax->amount)
                                <span class="fw-semibold">{{ number_format($tax->amount) }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $tax->currency == 'USD' ? 'bg-success' : 'bg-primary' }} text-white">
                                    {{ $tax->currency }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $tax->taxinvoices->count() > 0 ? 'bg-info' : 'bg-secondary' }} text-white">
                                    {{ $tax->taxinvoices->count() }} {{ Str::plural('invoice', $tax->taxinvoices->count()) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @if(Auth::user()->canDo(['edit_taxes']))
                                    <a href="{{ route('taxes.edit', $tax->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Tax">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    @endif

                                    @if($tax->taxinvoices->count() == 0)
                                        @if(Auth::user()->canDo(['delete_taxes']))
                                        <form action="{{ route('taxes.destroy', $tax->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this tax?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Delete Tax">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete tax in use">
                                            <i class="ri-lock-line"></i>
                                        </button>
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