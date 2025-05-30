@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage payment accounts and track balances</p>
        </div>
        @if (Auth::user()->canDo(['create_payment_accounts']))
        <a href="{{ route('payment_accounts.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i> Add Payment Account
        </a>
        @endif
    </div>

    <!-- Account Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Accounts Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-bank-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($payment_accounts) }}</h5>
                            <p class="text-muted small mb-0">Total Accounts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- UGX Balance Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-money-dollar-circle-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ number_format($payment_accounts->where('transaction_currency', 'UGX')->sum('balance')) }}</h5>
                            <p class="text-muted small mb-0">UGX Balance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- USD Balance Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-money-dollar-circle-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ number_format($payment_accounts->where('transaction_currency', 'USD')->sum('balance')) }}</h5>
                            <p class="text-muted small mb-0">USD Balance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-bank-line me-4 text-primary"></i>Payment Accounts
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
                            <th>Account Name</th>
                            <th>Account Number</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Balance</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payment_accounts as $payment_account)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $payment_account->name }}</span>
                                    @if($payment_account->others)
                                    <span class="text-muted small">{{ $payment_account->others }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $payment_account->account_number }}</td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    @if($payment_account->account_type == 'Bank')
                                        <i class="ri-bank-line me-1"></i>
                                    @elseif($payment_account->account_type == 'Cash')
                                        <i class="ri-money-dollar-box-line me-1"></i>
                                    @elseif($payment_account->account_type == 'Mobile Money')
                                        <i class="ri-smartphone-line me-1"></i>
                                    @elseif($payment_account->account_type == 'Digital')
                                        <i class="ri-global-line me-1"></i>
                                    @endif
                                    {{ $payment_account->account_type }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $payment_account->transaction_currency == 'USD' ? 'bg-success' : 'bg-primary' }} text-white">
                                    {{ $payment_account->transaction_currency }}
                                </span>
                            </td>
                            <td class="fw-semibold {{ $payment_account->balance < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($payment_account->balance) }}
                            </td>
                            <td>
                                <div class="d-flex gap-4">
                                    <a class="btn btn-sm btn-primary" href="{{ route('payment_accounts.show', $payment_account->id) }}" title="View Transactions">
                                        <i class="ri-exchange-dollar-line"></i>
                                    </a>
                                    @if (Auth::user()->canDo(['edit_payment_accounts']))
                                    <a class="btn btn-sm btn-secondary" href="{{ route('payment_accounts.edit', $payment_account->id) }}" title="Edit Account">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" title="Change Balance"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBalanceModal-{{ $payment_account->id }}">
                                        <i class="ri-coins-line"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Form for Editing Balance -->
                        <div class="modal fade" id="editBalanceModal-{{ $payment_account->id }}" tabindex="-1"
                            aria-labelledby="editBalanceLabel-{{ $payment_account->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('payment_accounts.update_balance', $payment_account->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="editBalanceLabel-{{ $payment_account->id }}">
                                                <i class="ri-coins-line me-4 text-warning"></i>
                                                Update Balance: {{ $payment_account->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="amount" class="form-label fw-semibold">Amount ({{ $payment_account->transaction_currency }})</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="ri-money-dollar-circle-line text-muted"></i>
                                                    </span>
                                                    <input type="number" class="form-control border-start-0 ps-0" id="amount"
                                                        name="amount" required placeholder="Enter amount">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="reason" class="form-label fw-semibold">Reason</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="ri-information-line text-muted"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0 ps-0" id="reason"
                                                        name="reason" required placeholder="Enter reason for adjustment">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="source" class="form-label fw-semibold">Source</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="ri-funds-line text-muted"></i>
                                                    </span>
                                                    <input type="text" class="form-control border-start-0 ps-0" id="source"
                                                        name="source" required placeholder="Enter source of funds">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="transaction_type" class="form-label fw-semibold">Transaction Type</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-end-0">
                                                        <i class="ri-exchange-funds-line text-muted"></i>
                                                    </span>
                                                    <select class="form-select border-start-0 ps-0" id="transaction_type"
                                                        name="transaction_type" required>
                                                        <option value="credit">Credit (Add)</option>
                                                        <option value="debit">Debit (Subtract)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1"></i> Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-save-line me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
