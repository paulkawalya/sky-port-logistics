@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage and track all financial transactions in the system</p>
        </div>
        @if(Auth::user()->canDo(['record_transaction']))
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Add Transaction
        </a>
        @endif
    </div>

    <!-- Transaction Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Transactions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-exchange-dollar-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $transactions->total() }}</h5>
                            <p class="text-muted small mb-0">Total Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Transactions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-arrow-down-circle-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $transactions->where('transaction_type', 'credit')->count() }}</h5>
                            <p class="text-muted small mb-0">Credit Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debit Transactions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="ri-arrow-up-circle-line text-danger"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $transactions->where('transaction_type', 'debit')->count() }}</h5>
                            <p class="text-muted small mb-0">Debit Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-money-dollar-circle-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ number_format($transactions->sum('amount')) }}</h5>
                            <p class="text-muted small mb-0">Total Amount</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-exchange-dollar-line me-4 text-primary"></i>Transactions List
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
                            <th>#</th>
                            <th>Date</th>
                            <th>Primary Account</th>
                            <th>Related Account</th>
                            <th>Particular</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Created By</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('M d, Y') }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $transaction->account($transaction->payment_account_id)->name }}</span>
                                    <span class="text-muted small">{{ $transaction->account($transaction->payment_account_id)->account_number }}</span>
                                </div>
                            </td>
                            <td>
                                @if(!empty($transaction->source_payment_account_id))
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $transaction->account($transaction->source_payment_account_id)->name }}</span>
                                    <span class="text-muted small">{{ $transaction->account($transaction->source_payment_account_id)->account_number }}</span>
                                </div>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="{{ $transaction->particular }}">
                                    {{ $transaction->particular ?: 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $transaction->transaction_type == 'credit' ? 'bg-success' : 'bg-danger' }} text-white">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $transaction->transaction_currency == 'USD' ? 'bg-success' : 'bg-primary' }} text-white">
                                    {{ $transaction->transaction_currency }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ number_format($transaction->amount) }}</td>
                            <td>{{ $transaction->user->name }}</td>
                            <td>
                                @if(Auth::user()->canDo(['delete_transaction_record']))
                                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit" title="Delete Transaction">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('layouts.data_tables')

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable with enhanced configuration
        const dataTable = $('#tabledata').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="ri-file-excel-2-line"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ri-file-pdf-line"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line"></i> Print',
                    className: 'btn btn-dark btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="ri-eye-line"></i> Columns',
                    className: 'btn btn-secondary btn-sm'
                }
            ],
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[1, 'desc']], // Sort by date descending by default
            deferRender: true,
            processing: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search transactions...",
                lengthMenu: "Show _MENU_ transactions per page",
                zeroRecords: "No transactions found",
                info: "Showing _START_ to _END_ of _TOTAL_ transactions",
                infoEmpty: "No transactions available",
                infoFiltered: "(filtered from _MAX_ total transactions)"
            },
            columnDefs: [
                { responsivePriority: 1, targets: 0 }, // ID column
                { responsivePriority: 2, targets: 1 }, // Date column
                { responsivePriority: 3, targets: 7 }, // Amount column
                { responsivePriority: 4, targets: 9 }  // Actions column
            ]
        });

        // Custom search box
        $('.dataTables_filter').addClass('d-none'); // Hide default search
        const searchBox = $('<div class="mb-3"><div class="input-group"><span class="input-group-text"><i class="ri-search-line"></i></span><input type="text" class="form-control" id="customSearch" placeholder="Search transactions..."></div></div>');
        $('.card-body').prepend(searchBox);

        $('#customSearch').on('keyup', function() {
            dataTable.search(this.value).draw();
        });

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
            $('.buttons-excel').click();
        });

        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-pdf').click();
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
            .dt-buttons { display: none !important; }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
