@extends('layouts.app')

@section('content')
<div class="container-fluid py-8">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-8">
        <div>
            <h4 class="fw-bold mb-8">{{ $title }}</h4>
            <p class="text-muted small mb-8">Track all expenses and payments made for requisitions</p>
        </div>
    </div>

    <!-- Expenses Stats Cards -->
    <div class="row g-3 mb-8">
        <!-- Total Expenses Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-money-dollar-circle-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-8">{{ count($requsiition_payments) }}</h5>
                            <p class="text-muted small mb-8">Total Expenses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- UGX Expenses Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-money-dollar-circle-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-8">{{ number_format($requsiition_payments->where('currency', 'UGX')->sum('amount')) }}</h5>
                            <p class="text-muted small mb-8">UGX Expenses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- USD Expenses Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-money-dollar-circle-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-8">{{ number_format($requsiition_payments->where('currency', 'USD')->sum('amount')) }}</h5>
                            <p class="text-muted small mb-8">USD Expenses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-8">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-8">
                    <i class="ri-exchange-dollar-line me-8 text-primary"></i>Expenses List
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
                            <li><a class="dropdown-item" href="#" id="export-excel"><i class="ri-file-excel-line me-8 text-success"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#" id="export-pdf"><i class="ri-file-pdf-line me-8 text-danger"></i>PDF</a></li>
                            <li><a class="dropdown-item" href="#" id="export-print"><i class="ri-printer-line me-8 text-dark"></i>Print</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Item</th>
                            <th>Supplier</th>
                            <th>Payment Mode</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($requsiition_payments) > 0)
                            @foreach($requsiition_payments as $requsiition_payment)
                            <tr>
                                <td>{{ str_pad($requsiition_payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ \Carbon\Carbon::parse($requsiition_payment->payment_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <i class="ri-price-tag-3-line me-8"></i> {{ $requsiition_payment->detail->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $requsiition_payment->detail->particular }}">
                                        {{ $requsiition_payment->detail->particular }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ $requsiition_payment->supplier->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        @if($requsiition_payment->mode_of_payment == 'Cash')
                                            <i class="ri-money-dollar-box-line me-8"></i>
                                        @elseif($requsiition_payment->mode_of_payment == 'Bank')
                                            <i class="ri-bank-line me-8"></i>
                                        @elseif($requsiition_payment->mode_of_payment == 'Mobile Money')
                                            <i class="ri-smartphone-line me-8"></i>
                                        @elseif($requsiition_payment->mode_of_payment == 'Digital')
                                            <i class="ri-global-line me-8"></i>
                                        @endif
                                        {{ $requsiition_payment->mode_of_payment }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $requsiition_payment->currency == 'USD' ? 'bg-success' : 'bg-primary' }} text-white">
                                        {{ $requsiition_payment->currency }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ number_format($requsiition_payment->amount) }}</td>
                                <td>
                                    <a href="{{ route('requisitions.show', $requsiition_payment->detail->requisition_id) }}" class="btn btn-sm btn-primary">
                                        <i class="ri-eye-line me-8"></i> Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ri-exchange-dollar-line text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3">No Expenses Found</h5>
                                        <p class="text-muted">There are no expenses to display at this time.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
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
        alert('Excel export functionality');
    });

    document.getElementById('export-pdf').addEventListener('click', function(e) {
        e.preventDefault();
        alert('PDF export functionality');
    });

    document.getElementById('export-print').addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
</script>
@endpush
