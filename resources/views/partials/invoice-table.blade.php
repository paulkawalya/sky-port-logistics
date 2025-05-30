<!-- Invoices Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-file-list-3-line me-2 text-primary"></i>{{ $title }}
            </h5>
            <div class="d-flex gap-4">
                <button class="btn btn-sm btn-outline-primary" id="refresh-table">
                    <i class="ri-refresh-line"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-download-line"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="#" id="export-excel"><i class="ri-file-excel-line me-4 text-success"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="#" id="export-pdf"><i class="ri-file-pdf-line me-4 text-danger"></i>PDF</a></li>
                        <li><a class="dropdown-item" href="#" id="export-csv"><i class="ri-file-text-line me-4 text-info"></i>CSV</a></li>
                        <li><a class="dropdown-item" href="#" id="export-print"><i class="ri-printer-line me-4 text-dark"></i>Print</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- DataTable wrapper with proper spacing -->
        <div class="table-responsive" style="width: 100%; margin: 0; padding: 0 1rem;">
            <table class="table table-hover align-middle mb-0" id="invoices-table" style="width: 100% !important;">
                <thead class="table-light">
                    <tr>
                        <th>Invoice No</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
