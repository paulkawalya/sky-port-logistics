
<div id="shippment_documents" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="shipmentDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shipmentDocModalLabel">
                    <i class="ri-file-add-line me-4 text-danger"></i>Add Shipment Document
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('shippment_documents.store')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="shippment_id" value="{{$shippment->id}}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-file-list-3-line me-4"></i>Document Details</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Shipment Summary -->
                            <div class="alert alert-light border-start border-4 border-danger mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-danger"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Shipment #{{ str_pad($shippment->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-0 small text-muted">
                                            Adding supporting documentation for this shipment
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Name -->
                            <div class="mb-4">
                                <label for="file_name" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-file-text-line"></i>
                                    <span>Document Name <span class="text-danger">*</span></span>
                                </label>
                                <input type="text" name="file_name" id="file_name" class="form-control" placeholder="Enter a descriptive name for this document" required>
                                <div class="form-text">Example: Bill of Lading, Packing List, Commercial Invoice, etc.</div>
                            </div>

                            <!-- File Upload -->
                            <div class="mb-4">
                                <label for="file_url" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-upload-cloud-line"></i>
                                    <span>Upload Document <span class="text-danger">*</span></span>
                                </label>
                                <div class="input-group">
                                    <input type="file" name="file_url" id="file_url" class="form-control" required>
                                </div>
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</div>
                            </div>

                            <!-- Preview Area -->
                            <div class="mt-4 d-none" id="file-preview-area">
                                <label class="form-label fw-semibold">Document Preview</label>
                                <div class="border rounded p-16 text-center bg-light">
                                    <div id="preview-content" class="mb-4"></div>
                                    <p class="mb-0 small text-muted" id="file-info"></p>
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
                            <i class="ri-save-line me-8"></i> Upload Document
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

        // File preview functionality
        $('#file_url').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                $('#file-preview-area').addClass('d-none');
                return;
            }

            // Show the preview area
            $('#file-preview-area').removeClass('d-none');

            // Display file info
            const fileSize = (file.size / 1024).toFixed(2) + ' KB';
            const fileType = file.type;
            $('#file-info').text(`${file.name} (${fileSize})`);

            // Preview for images
            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-content').html(`<img src="${e.target.result}" class="img-fluid" style="max-height: 200px;">`);
                }
                reader.readAsDataURL(file);
            } else {
                // For non-image files, show an icon
                let iconClass = 'ri-file-line';

                if (fileType.includes('pdf')) {
                    iconClass = 'ri-file-pdf-line';
                } else if (fileType.includes('word') || fileType.includes('doc')) {
                    iconClass = 'ri-file-word-line';
                } else if (fileType.includes('excel') || fileType.includes('sheet')) {
                    iconClass = 'ri-file-excel-line';
                }

                $('#preview-content').html(`<i class="${iconClass}" style="font-size: 64px; color: #6c757d;"></i>`);
            }
        });
    });
</script>
@endpush