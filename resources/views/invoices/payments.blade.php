@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Track all payment transactions in the system</p>
        </div>
    </div>

    <!-- Payment Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Payments Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-money-dollar-circle-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $payments->total() }}</h5>
                            <p class="text-muted small mb-0">Total Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- UGX Payments Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-money-dollar-circle-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ number_format($payments->filter(function($payment) { return $payment->invoice->transaction_currency == 'UGX'; })->sum('amount_paid')) }}</h5>
                            <p class="text-muted small mb-0">UGX Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- USD Payments Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-money-dollar-circle-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ number_format($payments->filter(function($payment) { return $payment->invoice->transaction_currency == 'USD'; })->sum('amount_paid')) }}</h5>
                            <p class="text-muted small mb-0">USD Payments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-exchange-dollar-line me-4 text-primary"></i>Payment Transactions
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
                            <li><a class="dropdown-item" href="#" id="export-csv"><i class="ri-file-text-line me-4 text-info"></i>CSV</a></li>
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
                            <th>Payment Mode</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Handled By</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $payment->invoice->shippment->customer->name }}</span>
                                    <span class="text-muted small"><i class="ri-phone-line me-1"></i>{{ $payment->invoice->shippment->customer->phone_number }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <i class="ri-bank-card-line me-1"></i> {{ $payment->mode_of_payment }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ number_format($payment->amount_paid) }}</td>
                            <td>
                                <span class="badge {{ $payment->invoice->transaction_currency == 'USD' ? 'bg-success' : 'bg-primary' }} text-white">
                                    {{ $payment->invoice->transaction_currency }}
                                </span>
                            </td>
                            <td>{{ $payment->user->name }}</td>
                            <td>
                                <a href="{{ route('invoices.show', $payment->invoice_id) }}" class="btn btn-sm btn-primary">
                                    <i class="ri-eye-line me-1"></i> View Invoice
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.data_tables')

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with advanced features
        const dataTable = $('#tabledata').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            dom: '<"top"<"row"<"col-md-6"l><"col-md-6"f>><"row"<"col-md-6"B><"col-md-6"p>>>rt<"bottom"<"row"<"col-md-6"i><"col-md-6"p>>>',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="ri-file-excel-line me-1"></i> Excel',
                    className: 'btn btn-sm btn-outline-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="ri-file-pdf-line me-1"></i> PDF',
                    className: 'btn btn-sm btn-outline-danger',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="ri-file-text-line me-1"></i> CSV',
                    className: 'btn btn-sm btn-outline-info',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line me-1"></i> Print',
                    className: 'btn btn-sm btn-outline-secondary',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search payments...",
                lengthMenu: "<span class='me-2'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ payments",
                paginate: {
                    first: "<i class='ri-arrow-left-s-line'></i>",
                    last: "<i class='ri-arrow-right-s-line'></i>",
                    next: "<i class='ri-arrow-right-s-line'></i>",
                    previous: "<i class='ri-arrow-left-s-line'></i>"
                }
            },
            initComplete: function() {
                // Add custom styling to buttons
                $('.dt-buttons .btn').addClass('me-2');
            }
        });

        // Refresh button functionality
        document.getElementById('refresh-table').addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;

            // Refresh the DataTable
            dataTable.ajax.reload(null, false);
            
            // Reset button after reload
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 1000);
        });

        // Export functionality
        document.getElementById('export-excel').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-excel').click();
        });

        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-pdf').click();
        });

        document.getElementById('export-csv').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-csv').click();
        });

        document.getElementById('export-print').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-print').click();
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
            .dt-buttons .btn {
                margin-right: 8px;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
