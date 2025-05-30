
<div id="notify_approvers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="notifyApproversModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notifyApproversModalLabel">
                    <i class="ri-notification-line me-4 text-danger"></i>Notify Approvers
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('approvals.update',$requisition->id)}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-mail-send-line me-4"></i>Email Notification</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Requisition Summary -->
                            <div class="alert alert-light border-start border-4 border-danger mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-danger"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Requisition #{{ str_pad($requisition->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-0 small text-muted">
                                            Total Amount: <span class="fw-semibold">{{ number_format($requisition->amount($requisition->id)) }} {{ $requisition->currency }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Recipients -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold label-with-icon">
                                    <i class="ri-user-received-line"></i>
                                    <span>Recipients</span>
                                </label>
                                <div class="p-8 rounded border bg-light">
                                    @php
                                        $approvers = $requisition->approvers($requisition->id);
                                    @endphp

                                    @if(count($approvers) > 0)
                                        @foreach($approvers as $approver)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="ri-mail-line me-4 text-muted"></i>
                                                <span>{{ $approver }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted">
                                            <p class="mb-0">No approvers found for this requisition.</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-text">Email notifications will be sent to these approvers</div>
                            </div>

                            <!-- Confirmation -->
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="ri-alert-line fs-4 me-4"></i>
                                    <div>
                                        <p class="mb-0">Are you sure you want to send email notifications to the approvers? This action cannot be undone.</p>
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
                            <i class="ri-send-plane-line me-8"></i> Send Notifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>