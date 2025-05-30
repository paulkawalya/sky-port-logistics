
<div id="add_shippments" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="uploadShipmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadShipmentsModalLabel">
                    <i class="ri-ship-line me-4 text-danger"></i>Upload Shipments
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('shippments.store')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="transit_id" value="{{$transit->id}}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-upload-cloud-line me-4"></i>Import Shipments</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Template Download -->
                            <div class="mb-4 text-center">
                                <a href="{{ asset('files/tamplate.xlsx') }}" class="btn btn-outline-primary px-8">
                                    <i class="ri-download-line me-8"></i> Download Sample Template
                                </a>
                                <div class="form-text mt-2">Use this template to prepare your shipment data</div>
                            </div>

                            <!-- Important Information -->
                            <div class="alert alert-light border-start border-4 border-info mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-4 me-4 text-info"></i>
                                    <div>
                                        <p class="mb-2 fw-medium">Important Information:</p>
                                        <ul class="mb-0 ps-4">
                                            <li>Empty rows will be skipped during import</li>
                                            <li>Duplicate shipments for the same container will be automatically detected and skipped</li>
                                            <li>Make sure all required fields are filled in the spreadsheet</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div class="mb-4">
                                <label for="file_url" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-file-excel-line"></i>
                                    <span>Upload Excel File <span class="text-danger">*</span></span>
                                </label>
                                <div class="input-group">
                                    <input type="file" name="file_url" id="file_url" class="form-control" accept=".xlsx,.xls" required>
                                </div>
                                <div class="form-text">Supported formats: Excel (.xlsx, .xls)</div>
                            </div>

                            <!-- Preview Area -->
                            <div class="mt-4 d-none" id="file-preview-area">
                                <label class="form-label fw-semibold">File Information</label>
                                <div class="border rounded p-16 text-center bg-light">
                                    <div id="preview-content" class="mb-4">
                                        <i class="ri-file-excel-line" style="font-size: 64px; color: #217346;"></i>
                                    </div>
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
                            <i class="ri-upload-cloud-line me-8"></i> Import Shipments
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
            $('#file-info').text(`${file.name} (${fileSize})`);
        });
    });
</script>
@endpush