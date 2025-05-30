@extends('layouts.app')

@section('content')
@if(!isset($customers))
    @php
        $customers = \App\Models\Customer::all();
    @endphp
@endif
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage and track all quotations and manual invoices</p>
        </div>
        @if(Auth::user()->canDo(['create_quotations']))
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_new_quotation">
            <i class="ri-add-line me-8"></i> New Invoice or Quotation
        </button>
        @endif
    </div>

    <!-- Quotations Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Quotations Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-file-list-3-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $quotations->count() }}</h5>
                            <p class="text-muted small mb-0">Total Documents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-bill-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $quotations->where('transaction_type', 'invoice')->count() }}</h5>
                            <p class="text-muted small mb-0">Invoices</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quotations Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-file-paper-2-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $quotations->where('transaction_type', 'quotation')->count() }}</h5>
                            <p class="text-muted small mb-0">Quotations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quotations Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-file-list-3-line me-4 text-primary"></i>{{ $title }}
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabledata">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Created By</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $quotation)
                        <tr>
                            <td>{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ \Carbon\Carbon::parse($quotation->date_quoted)->format('M d, Y') }}</td>
                            <td>{{ $quotation->user->name }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $quotation->customer->name }}</span>
                                    <span class="text-muted small"><i class="ri-phone-line me-8"></i>{{ $quotation->customer->phone_number }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $quotation->transaction_type == 'invoice' ? 'bg-success' : 'bg-info' }} text-white">
                                    {{ ucfirst($quotation->transaction_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $quotation->transaction_currency == 'USD' ? 'bg-success' : 'bg-primary' }} text-white">
                                    {{ $quotation->transaction_currency }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ number_format($quotation->totalAmount($quotation->id)) }}</td>
                            <td class="fw-semibold text-success">
                                {{ $quotation->transaction_type!="invoice" ? "N/A": number_format($quotation->paid($quotation->id)) }}
                            </td>
                            <td class="fw-semibold {{ $quotation->transaction_type == 'invoice' && $quotation->balance($quotation->id) > 0 ? 'text-danger' : '' }}">
                                {{ $quotation->transaction_type!="invoice" ? "N/A": number_format($quotation->balance($quotation->id)) }}
                            </td>
                            <td>
                                <div class="d-flex gap-4">
                                    <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    @if(Auth::user()->canDo(['edit_quotations']))
                                    <button type="button" class="btn btn-sm btn-secondary" title="Edit"
                                            data-toggle="modal"
                                            data-target="#edit_quotation"
                                            data-id="{{ $quotation->id }}"
                                            data-customer-id="{{ $quotation->customer_id }}"
                                            data-date-quoted="{{ $quotation->date_quoted }}"
                                            data-transaction-type="{{ $quotation->transaction_type }}"
                                            data-transaction-currency="{{ $quotation->transaction_currency }}"
                                            data-container-air-no="{{ $quotation->container_air_no }}"
                                            data-entry-no="{{ $quotation->entry_no }}"
                                            data-weight="{{ $quotation->weight }}"
                                            data-volume="{{ $quotation->volume }}"
                                            data-packages="{{ $quotation->packages }}"
                                            data-description="{{ $quotation->description }}">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('models.add_quotation')
@include('models.edit_quotation')

@include('layouts.data_tables')

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with advanced features
        const dataTable = $('#tabledata').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            dom: '<"top"<"row"<"col-md-6"l><"col-md-6"f>><"row"<"col-md-6"B><"col-md-6"p>>>rt<"bottom"<"row"<"col-md-6"i><"col-md-6"p>>>',
            columnDefs: [
                {
                    targets: 0, // ID column
                    type: 'num',
                    render: function(data, type, row) {
                        // For display, keep the leading zeros
                        if (type === 'display') {
                            return data;
                        }
                        // For sorting, use the numeric value
                        return parseInt(data);
                    }
                }
            ],
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="ri-file-excel-line me-1"></i> Excel',
                    className: 'btn btn-sm btn-outline-success',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="ri-file-pdf-line me-1"></i> PDF',
                    className: 'btn btn-sm btn-outline-danger',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="ri-file-text-line me-1"></i> CSV',
                    className: 'btn btn-sm btn-outline-info',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line me-1"></i> Print',
                    className: 'btn btn-sm btn-outline-secondary',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search quotations...",
                lengthMenu: "<span class='me-2'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ quotations",
                paginate: {
                    first: "<i class='ri-arrow-left-s-line'></i>",
                    last: "<i class='ri-arrow-right-s-line'></i>",
                    next: "<i class='ri-arrow-right-s-line'></i>",
                    previous: "<i class='ri-arrow-left-s-line'></i>"
                }
            },
            initComplete: function() {
                // Add custom styling to buttons
                $('.dt-buttons .btn').addClass('me-2');
            }
        });

        // Refresh button functionality
        document.getElementById('refresh-table').addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;

            // Refresh the DataTable
            dataTable.ajax.reload(null, false);
            
            // Reset button after reload
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }, 1000);
        });

        // Export functionality
        document.getElementById('export-excel').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-excel').click();
        });

        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-pdf').click();
        });

        document.getElementById('export-csv').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-csv').click();
        });

        document.getElementById('export-print').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-print').click();
        });

        // Add print styles
        const style = document.createElement('style');
        style.textContent = `
            @media print {
                .container-fluid { padding: 0 !important; }
                .card { border: none !important; box-shadow: none !important; }
                .card-header, .btn, .dropdown, .card-footer, .pagination { display: none !important; }
                .table { width: 100% !important; }
                .table th, .table td { padding: 8px !important; }
            }
            .dt-buttons .btn {
                margin-right: 8px;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush

@section('scripts')
<script>
    function populateEditForm(button) {
        const modal = $('#edit_quotation');
        if (!modal.length) {
            console.error('Edit modal not found');
            return false;
        }

        // Set form action
        modal.find('form').attr('action', '/quotations/' + button.data('id'));

        // Define fields to populate
        const fields = [
            {id: '#customer_id', value: button.data('customer-id'), type: 'select'},
            {id: '#date_quoted', value: button.data('date-quoted')},
            {id: '#transaction_type', value: button.data('transaction-type'), type: 'select'},
            {id: '#transaction_currency', value: button.data('transaction-currency'), type: 'select'},
            {id: '#container_air_no', value: button.data('container-air-no')},
            {id: '#entry_no', value: button.data('entry-no')},
            {id: '#weight', value: button.data('weight')},
            {id: '#volume', value: button.data('volume')},
            {id: '#packages', value: button.data('packages')},
            {id: '#description', value: button.data('description')}
        ];

        // Try populating fields with retries
        let success = true;
        fields.forEach(field => {
            const element = modal.find(field.id);
            if (!element.length) {
                console.error(`Element not found: ${field.id}`);
                success = false;
                return;
            }

            let valueSet = false;
            const trySetValue = (retryCount = 0) => {
                if (field.type === 'select') {
                    element.val(field.value).trigger('change');
                } else {
                    element.val(field.value);
                }

                if (element.val() == field.value) {
                    valueSet = true;
                } else if (retryCount < 2) {
                    setTimeout(() => trySetValue(retryCount + 1), 100);
                }
            };
            trySetValue();

            if (!valueSet) {
                console.error(`Failed to set value for ${field.id}`);
                success = false;
            }
        });

        return success;
    }

    $(document).ready(function() {
        // Handle edit button clicks
        $(document).on('click', '[data-target="#edit_quotation"]', function(e) {
            e.preventDefault();
            const button = $(this);
            const modal = $('#edit_quotation');

            // Store button data in modal's data
            modal.data('quotation-data', button.data());

            // Show modal
            modal.modal('show');
        });

        // Handle modal shown event to populate form
        $('#edit_quotation').on('shown.bs.modal', function() {
            const modal = $(this);
            const data = modal.data('quotation-data');

            if (!data) {
                console.error('No quotation data found');
                return;
            }

            // Set form action
            modal.find('form').attr('action', '/quotations/' + data.id);

            // Populate fields
            modal.find('#customer_id').val(data.customerId).trigger('change');
            modal.find('#date_quoted').val(data.dateQuoted);
            modal.find('#transaction_type').val(data.transactionType).trigger('change');
            modal.find('#transaction_currency').val(data.transactionCurrency).trigger('change');
            modal.find('#container_air_no').val(data.containerAirNo);
            modal.find('#entry_no').val(data.entryNo);
            modal.find('#weight').val(data.weight);
            modal.find('#volume').val(data.volume);
            modal.find('#packages').val(data.packages);
            modal.find('#description').val(data.description);

            console.log('Form populated with:', data);
        });
    });
</script>

@push('scripts')
    <script src="{{ asset('js/edit-quotation-form.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#customer_id").chosen({
                width: "100%",
                search_contains: true
            });
        });
    </script>
@endpush

@endsection
