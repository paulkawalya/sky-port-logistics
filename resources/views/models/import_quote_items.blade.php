<div id="import_excel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="importExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExcelModalLabel">
                    <i class="ri-file-excel-line me-4 text-danger"></i>Import Items from Excel
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{ route('quotation_details.import') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-upload-cloud-2-line me-4"></i>Upload Spreadsheet</h6>
                        </div>
                        <div class="card-body p-16">
                            <div class="mb-4">
                                <label for="excel_file" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-attachment-line"></i>
                                    <span>Select Excel File (.xlsx, .xls) <span class="text-danger">*</span></span>
                                </label>
                                <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                <div class="form-text mt-8">
                                    <strong>Expected File Layout:</strong>
                                    <ul class="ps-12 mt-4 text-muted small">
                                        <li>Row 1: Header Row</li>
                                        <li>Row 2: Blank Spacer</li>
                                        <li>Row 3+: Data rows start here</li>
                                        <li>Column B: Manufacturer & Model</li>
                                        <li>Column C: Technical Specifications</li>
                                        <li>Column D: Quantity (QTY)</li>
                                        <li>Column F: Unit Price</li>
                                    </ul>
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
                            <i class="ri-upload-2-line me-8"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
