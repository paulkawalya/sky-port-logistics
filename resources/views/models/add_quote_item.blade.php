
<div id="add_item" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addQuoteItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addQuoteItemModalLabel">
                    <i class="ri-add-circle-line me-4 text-danger"></i>Add Quotation Item
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('quotation_details.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="quotation_id" value="{{$quotation->id}}">

                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-danger py-3">
                                    <h6 class="mb-0 text-white"><i class="ri-information-line me-4"></i>Item Details</h6>
                                </div>
                                <div class="card-body p-16">
                                    <!-- Item Description -->
                                    <div class="mb-4">
                                        <label for="particular" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-text"></i>
                                            <span>Item Description <span class="text-danger">*</span></span>
                                        </label>
                                        <textarea name="particular" id="particular" class="form-control" rows="3" style="min-height: 100px;" required></textarea>
                                        <div class="form-text">Enter a clear description of the item or service</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-danger py-3">
                                    <h6 class="mb-0 text-white"><i class="ri-calculator-line me-4"></i>Quantity & Price</h6>
                                </div>
                                <div class="card-body p-16">
                                    <!-- Quantity -->
                                    <div class="mb-4">
                                        <label for="quantity" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-numbers-line"></i>
                                            <span>Quantity <span class="text-danger">*</span></span>
                                        </label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                                    </div>

                                    <!-- Unit Price -->
                                    <div class="mb-4">
                                        <label for="unit_price" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-money-dollar-circle-line"></i>
                                            <span>Unit Price <span class="text-danger">*</span></span>
                                        </label>
                                        <input type="number" name="unit_price" id="unit_price" class="form-control" min="0" required>
                                    </div>

                                    <!-- Total Price (Calculated) -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold label-with-icon">
                                            <i class="ri-funds-line"></i>
                                            <span>Total Price</span>
                                        </label>
                                        <div class="form-control bg-light" id="total_price">0</div>
                                        <div class="form-text">Quantity × Unit Price</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end mt-4 gap-4">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-8">
                            <i class="ri-save-line me-8"></i> Save Item
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

        // Calculate total price
        function calculateTotal() {
            const quantity = parseFloat($("#quantity").val()) || 0;
            const unitPrice = parseFloat($("#unit_price").val()) || 0;
            const total = quantity * unitPrice;
            $("#total_price").text(total.toLocaleString());
        }

        // Update total when inputs change
        $("#quantity, #unit_price").on('input', calculateTotal);
    });
</script>
@endpush