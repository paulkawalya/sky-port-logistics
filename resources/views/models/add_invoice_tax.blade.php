
<div id="add_applicable_taxes" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addInvoiceTaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInvoiceTaxModalLabel">
                    <i class="ri-percent-line me-4 text-danger"></i>Add Applicable Taxes
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('invoice_taxes.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{$invoice->id}}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-calculator-line me-4"></i>Tax Selection</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Invoice Summary -->
                            <div class="alert alert-light border-start border-4 border-danger mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-danger"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-0 small text-muted">
                                            Total Amount: <span class="fw-semibold">{{ number_format($invoice->amount($invoice->id)) }} {{$invoice->transaction_currency}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Tax Selection -->
                            <div class="mb-4">
                                <label for="tax_id" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-government-line"></i>
                                    <span>Select Taxes <span class="text-danger">*</span></span>
                                </label>
                                <select name="taxes[]" multiple id="tax_id" class="form-select" required>
                                    @foreach($taxes as $tax)
                                        <option value="{{ $tax->id }}">{{ $tax->tax_name." ".$tax->percentage_tax."% ".$tax->currency.$tax->amount }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">You can select multiple taxes to apply to this invoice</div>
                            </div>

                            <!-- Tax Information -->
                            <div class="alert alert-light border-start border-4 border-info mb-0">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-info"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Tax Information</p>
                                        <p class="mb-0 small">
                                            Selected taxes will be applied to the invoice total. Percentage taxes will be calculated based on the subtotal.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-4">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-8">
                            <i class="ri-save-line me-8"></i> Apply Taxes
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
        // Initialize chosen select
        $("#tax_id").chosen({
            width: "100%",
            no_results_text: "No taxes found",
            placeholder_text_multiple: "Select taxes to apply"
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
    });
</script>
@endpush