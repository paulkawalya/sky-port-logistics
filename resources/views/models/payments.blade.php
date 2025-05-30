
<div id="add_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="recordPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordPaymentModalLabel">
                    <i class="ri-money-dollar-circle-line me-4 text-success"></i>Record Payment
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('payments.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{$invoice->id}}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-file-list-3-line me-4"></i>Invoice Payment</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Invoice Summary -->
                            <div class="alert alert-light border-start border-4 border-success mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-success"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-0 small text-muted">
                                            Balance Due: <span class="fw-semibold">{{ number_format($invoice->balance($invoice->id)) }} {{$invoice->transaction_currency}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Date -->
                            <div class="mb-4">
                                <label for="payment_date" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-calendar-line"></i>
                                    <span>Payment Date <span class="text-danger">*</span></span>
                                </label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>

                            <!-- Amount Paid -->
                            <div class="mb-4">
                                <label for="amount_paid" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-money-dollar-circle-line"></i>
                                    <span>Amount Paid ({{$invoice->transaction_currency}}) <span class="text-danger">*</span></span>
                                </label>
                                <input type="number" name="amount_paid" id="amount_paid" value="{{ $invoice->balance($invoice->id) }}" class="form-control" min="0" step="0.01" required>
                                <div class="form-text">Enter the amount received from the customer</div>
                            </div>

                            <!-- Payment Method -->
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

                            <!-- Account to Credit -->
                            <div class="mb-4">
                                <label for="payment_account_id" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-bank-line"></i>
                                    <span>Account to Credit <span class="text-danger">*</span></span>
                                </label>
                                <select name="payment_account_id" id="payment_account_id" class="form-select" required>
                                    <option value="">Select account</option>
                                </select>
                                <div class="form-text">Select the account where the payment will be recorded</div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="narrative" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-text"></i>
                                    <span>Description</span>
                                </label>
                                <input type="text" name="narrative" id="narrative" class="form-control" placeholder="e.g., Payment for Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}">
                                <div class="form-text">Enter any additional details about this payment</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-4">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-8">
                            <i class="ri-save-line me-8"></i> Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize chosen selects
        $("#mode_of_payment, #payment_account_id").chosen({
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
            var currency = "{{$invoice->transaction_currency}}";

            if (mode_of_payment) {
                $.get('/get_accounts/' + mode_of_payment + '/' + currency, function(data) {
                    $("#payment_account_id").chosen('destroy');
                    $("#payment_account_id").empty();
                    $('#payment_account_id').append("<option value=''>Select account</option>");

                    $.each(data, function(index, account) {
                        $('#payment_account_id').append("<option value=" + account.id + ">" + account.name + " " + account.account_number + " " + account.transaction_currency + "</option>");
                    });

                    $("#payment_account_id").chosen({
                        width: "100%"
                    });
                });
            }
        });
    });
</script>
@endpush