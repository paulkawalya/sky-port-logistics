@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Create a new financial transaction in the system</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Transactions
        </a>
    </div>

    <!-- Create Transaction Card -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-exchange-dollar-line me-4 text-primary"></i>Transaction Details
                    </h5>
                </div>
                <div class="card-body p-16">
                    <form action="{{ route('transactions.store') }}" method="post">
                        @csrf

                        <!-- Source Account -->
                        <div class="mb-4">
                            <label for="payment_account_id" class="form-label fw-semibold">Source Account <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-bank-line text-muted"></i>
                                </span>
                                <select name="payment_account_id" id="payment_account_id" class="form-select border-start-0 ps-0" required>
                                    <option value="">Select source account</option>
                                    @foreach($payment_accounts as $payment_account)
                                        <option value="{{ $payment_account->id }}">
                                            {{ $payment_account->account_type }} - {{ $payment_account->name }} ({{ $payment_account->account_number }}) - {{ $payment_account->transaction_currency }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text">Select the primary account for this transaction</div>
                        </div>

                        <!-- Destination Account -->
                        <div class="mb-4">
                            <label for="source_payment_account_id" class="form-label fw-semibold">Destination Account</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-bank-card-line text-muted"></i>
                                </span>
                                <select name="source_payment_account_id" id="source_payment_account_id" class="form-select border-start-0 ps-0">
                                    <option value="">Select destination account (optional)</option>
                                </select>
                            </div>
                            <div class="form-text">For transfers between accounts, select the destination account</div>
                        </div>

                        <div class="row">
                            <!-- Transaction Date -->
                            <div class="col-md-6 mb-4">
                                <label for="transaction_date" class="form-label fw-semibold">Transaction Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-calendar-line text-muted"></i>
                                    </span>
                                    <input type="date" name="transaction_date" id="transaction_date" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="form-control border-start-0 ps-0" required>
                                </div>
                            </div>

                            <!-- Transaction Type -->
                            <div class="col-md-6 mb-4">
                                <label for="transaction_type" class="form-label fw-semibold">Transaction Type <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-exchange-funds-line text-muted"></i>
                                    </span>
                                    <select name="transaction_type" id="transaction_type" class="form-select border-start-0 ps-0" required>
                                        <option value="">Select transaction type</option>
                                        @foreach($transaction_types as $transaction_type)
                                            <option value="{{ $transaction_type }}">
                                                {{ ucfirst($transaction_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-text">Credit increases account balance, Debit decreases it</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Amount -->
                            <div class="col-md-6 mb-4">
                                <label for="amount" class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-money-dollar-circle-line text-muted"></i>
                                    </span>
                                    <input type="number" name="amount" id="amount" min="0" step="any" class="form-control border-start-0 ps-0" placeholder="Enter amount" required>
                                </div>
                            </div>

                            <!-- Particular -->
                            <div class="col-md-6 mb-4">
                                <label for="particular" class="form-label fw-semibold">Particular</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-file-text-line text-muted"></i>
                                    </span>
                                    <input type="text" name="particular" id="particular" class="form-control border-start-0 ps-0" placeholder="Enter transaction description">
                                </div>
                                <div class="form-text">Brief description of the transaction purpose</div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('transactions.index') }}" class="btn btn-light me-4">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Save Transaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        $("#payment_account_id, #source_payment_account_id, #transaction_type").select2({
            theme: "bootstrap-5",
            placeholder: "Select an option",
            allowClear: true
        });

        // Handle source account change to populate destination accounts
        $('#payment_account_id').on('change', function(e) {
            var payment_account_id = e.target.value;

            if (!payment_account_id) {
                return;
            }

            // Show loading indicator
            $('#source_payment_account_id').html('<option value="">Loading accounts...</option>');

            // Fetch related accounts
            $.get('/related_accounts/' + payment_account_id, function(data) {
                // Clear and repopulate the dropdown
                $("#source_payment_account_id").empty();
                $('#source_payment_account_id').append('<option value="">Select destination account (optional)</option>');

                $.each(data, function(index, account) {
                    $('#source_payment_account_id').append(
                        '<option value="' + account.id + '">' +
                        account.account_type + ' - ' +
                        account.name + ' (' +
                        account.account_number + ') - ' +
                        account.transaction_currency +
                        '</option>'
                    );
                });

                // Refresh select2
                $("#source_payment_account_id").trigger('change');
            });
        });

        // Form validation
        $('form').on('submit', function(e) {
            var isValid = true;

            // Check required fields
            if (!$('#payment_account_id').val()) {
                $('#payment_account_id').addClass('is-invalid');
                isValid = false;
            }

            if (!$('#transaction_type').val()) {
                $('#transaction_type').addClass('is-invalid');
                isValid = false;
            }

            if (!$('#amount').val() || parseFloat($('#amount').val()) <= 0) {
                $('#amount').addClass('is-invalid');
                isValid = false;
            }

            if (!$('#transaction_date').val()) {
                $('#transaction_date').addClass('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });

        // Clear validation errors on input
        $('input, select').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-label {
        font-size: 14px;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 12px;
        color: #6c757d;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
    }
</style>
@endpush

