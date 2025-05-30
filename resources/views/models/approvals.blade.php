
<div id="add_requisition_status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">
                    <i class="ri-checkbox-circle-line me-4 text-success"></i>Approve Requisition
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('approvals.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="requisition_id" value="{{$requisition->id}}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0"><i class="ri-file-list-3-line me-4 text-primary"></i>Requisition Approval</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Requisition Summary -->
                            <div class="alert alert-light border-start border-4 border-primary mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-primary"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Requisition #{{ str_pad($requisition->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-0 small text-muted">
                                            Total Amount: <span class="fw-semibold">{{ number_format($requisition->amount($requisition->id)) }} {{ $requisition->currency }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Selection -->
                            <div class="mb-4">
                                <label for="status" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-toggle-line"></i>
                                    <span>Approval Status <span class="text-danger">*</span></span>
                                </label>
                                <select name="status" id="status" class="form-select" required>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Select whether to approve or reject this requisition</div>
                            </div>

                            <!-- Remarks -->
                            <div class="mb-8">
                                <label for="remarks" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-chat-quote-line"></i>
                                    <span>Remarks <span class="text-danger">*</span></span>
                                </label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="3" required></textarea>
                                <div class="form-text">Provide a reason for your decision</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-4">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success px-8" id="submit-approval">
                            <i class="ri-check-line me-8"></i> Submit Decision
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
        // Initialize status select with Chosen
        $("#status").chosen({
            width: "100%",
            no_results_text: "No results match",
            allow_single_deselect: false
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

        // Change button color based on status selection
        $("#status").on('change', function() {
            const status = $(this).val();
            const submitBtn = $("#submit-approval");

            if (status === 'approved') {
                submitBtn.removeClass('btn-danger').addClass('btn-success');
                submitBtn.html('<i class="ri-check-line me-8"></i> Approve Requisition');
            } else if (status === 'rejected') {
                submitBtn.removeClass('btn-success').addClass('btn-danger');
                submitBtn.html('<i class="ri-close-line me-8"></i> Reject Requisition');
            }
        });

        // Trigger change event to set initial button state
        $("#status").trigger('change');
    });
</script>
@endpush