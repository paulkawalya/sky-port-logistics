
<div id="add_requisition_item" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">
                    <i class="ri-add-circle-line me-4 text-primary"></i>Add Requisition Item
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('requisition_details.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="requisition_id" value="{{$requisition->id}}">

                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0"><i class="ri-information-line me-4 text-primary"></i>Item Details</h6>
                                </div>
                                <div class="card-body p-16">
                                    <!-- Expense Category -->
                                    <div class="mb-4">
                                        <label for="expense_category_id" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-price-tag-3-line"></i>
                                            <span>Expense Category</span>
                                        </label>
                                        <select name="expense_category_id" id="expense_category_id" class="form-select">
                                            <option value="">Select expense category</option>
                                            @foreach($expense_categories as $expense_category)
                                                <option value="{{ $expense_category->id }}">{{ $expense_category->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Select the category for this expense</div>
                                    </div>

                                    <!-- Particular -->
                                    <div class="mb-4">
                                        <label for="particular" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-text"></i>
                                            <span>Item Description <span class="text-danger">*</span></span>
                                        </label>
                                        <textarea name="particular" id="particular" class="form-control" rows="3" style="min-height: 100px;" required></textarea>
                                        <div class="form-text">Enter a clear description of the item</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0"><i class="ri-calculator-line me-4 text-primary"></i>Quantity & Cost</h6>
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

                                    <!-- Unit Cost -->
                                    <div class="mb-4">
                                        <label for="estimated_cost" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-money-dollar-circle-line"></i>
                                            <span>Unit Cost <span class="text-danger">*</span></span>
                                        </label>
                                        <input type="number" name="estimated_cost" id="estimated_cost" class="form-control" min="0" required>
                                    </div>

                                    <!-- Total Cost (Calculated) -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold label-with-icon">
                                            <i class="ri-funds-line"></i>
                                            <span>Total Cost</span>
                                        </label>
                                        <div class="form-control bg-light" id="total_cost">0</div>
                                        <div class="form-text">Quantity × Unit Cost</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Justification - Full Width -->
                        <div class="col-12">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0"><i class="ri-file-text-line me-4 text-primary"></i>Justification</h6>
                                </div>
                                <div class="card-body p-16">
                                    <div class="mb-4">
                                        <label for="reason" class="form-label fw-semibold label-with-icon">
                                            <i class="ri-chat-quote-line"></i>
                                            <span>Justification <span class="text-danger">*</span></span>
                                        </label>
                                        <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
                                        <div class="form-text">Explain why this item is needed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end mt-8 gap-4">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger px-8">
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
        // Initialize chosen select
        $("#expense_category_id").chosen({
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

        // Calculate total cost
        function calculateTotal() {
            const quantity = parseFloat($("#quantity").val()) || 0;
            const unitCost = parseFloat($("#estimated_cost").val()) || 0;
            const total = quantity * unitCost;
            $("#total_cost").text(total.toLocaleString());
        }

        // Update total when inputs change
        $("#quantity, #estimated_cost").on('input', calculateTotal);
    });
</script>
@endpush