@extends('layouts.app')

@section('styles')
@parent
<style>
    /* Fix DataTable width and layout */
    #tabledata {
        width: 100% !important;
        margin: 0;
    }
    
    .dataTables_wrapper {
        width: 100% !important;
        margin: 0 !important;
    }
    
    .dataTables_scroll {
        width: 100% !important;
    }
    
    .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    
    .dataTables_scrollHeadInner table {
        width: 100% !important;
    }
    
    .dataTables_scrollBody {
        width: 100% !important;
    }
    
    .dataTables_scrollBody table {
        width: 100% !important;
    }
    
    /* Fix table header alignment */
    .dataTables_scrollHeadInner {
        padding-right: 0 !important;
    }
    
    /* Ensure table cells don't wrap */
    #tabledata th,
    #tabledata td {
        white-space: nowrap;
        vertical-align: middle;
    }
    
    /* Add some padding to the card */
    .card-body {
        padding: 1.25rem !important;
    }
    
    /* Fix the scrollbar appearance */
    ::-webkit-scrollbar {
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

<style>
    /* Custom styles for requisitions list */
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .action-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .view-btn {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .edit-btn {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    /* DataTables custom styles */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        margin: 8px 0;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
    }
    
    /* Card hover and active states */
    .card-filter {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .card-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .card-filter.active {
        border: 2px solid #0d6efd !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage and track all requisitions in the system</p>
        </div>
        @if(Auth::user()->canDo(['add_requisition']))
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_new_requisition">
            <i class="ri-add-line me-8"></i> New Requisition
        </button>
        @endif
    </div>

    <!-- Requisition Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Requisitions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 card-filter {{ !request('status') ? 'active' : '' }}" data-status="">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-file-list-3-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $totalCount }}</h5>
                            <p class="text-muted small mb-0">Total Requisitions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requisitions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 card-filter {{ request('status') == 'pending' ? 'active' : '' }}" data-status="pending">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(255, 193, 7, 0.1);">
                            <i class="ri-time-line text-warning"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $pendingCount }}</h5>
                            <p class="text-muted small mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Requisitions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 card-filter {{ request('status') == 'approved' ? 'active' : '' }}" data-status="approved">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-checkbox-circle-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $approvedCount }}</h5>
                            <p class="text-muted small mb-0">Approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Requisitions Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 card-filter {{ request('status') == 'rejected' ? 'active' : '' }}" data-status="rejected">
                <div class="card-body p-8">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-8 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="ri-close-circle-line text-danger"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $rejectedCount }}</h5>
                            <p class="text-muted small mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requisitions Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-file-list-3-line me-4 text-primary"></i>Requisitions List
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
                            <li><a class="dropdown-item" href="#" id="export-print"><i class="ri-printer-line me-4 text-dark"></i>Print</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="tabledata" style="width: 100% !important;">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('models.add_requisition')

@include('layouts.container_data_tables')

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to update URL with new filter
        function updateStatusFilter(status) {
            const url = new URL(window.location.href);
            
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            
            // Reload the page with the new filter
            window.location.href = url.toString();
        }
        
        // Handle card filter clicks
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.card-filter');
            if (card) {
                const status = card.getAttribute('data-status');
                updateStatusFilter(status);
            }
        });
        
        // Handle keyboard navigation for accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const activeElement = document.activeElement;
                const card = activeElement.closest('.card-filter');
                if (card) {
                    e.preventDefault();
                    const status = card.getAttribute('data-status');
                    updateStatusFilter(status);
                }
            }
        });

        // Get the current status filter from URL
        const urlParams = new URLSearchParams(window.location.search);
        const statusFilter = urlParams.get('status') || '';

        // Initialize DataTable with server-side processing
        const dataTable = $('#tabledata').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 25,
            stateSave: true, // Save state (page, length, filter, etc.)
            deferRender: true, // Defer rendering for better performance
            ajax: {
                url: '{{ route("requisitions.datatable") }}',
                type: 'GET',
                data: function(d) {
                    d.status = statusFilter; // Pass the status filter to the server
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables error:', error);
                    console.error('Error type:', thrown);
                    console.error('Status:', xhr.status);
                    console.error('Response:', xhr.responseText);
                    
                    let errorMsg = 'Error loading data. Please refresh the page or try again later.';
                    
                    // Try to parse the response if it's JSON
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.error) {
                            errorMsg = response.error;
                        } else if (response.message) {
                            errorMsg = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                    
                    // Show detailed error message
                    $('#tabledata tbody').html(`
                        <tr>
                            <td colspan="9" class="text-center text-danger">
                                <div class="mb-2"><i class="ri-error-warning-line me-2"></i>${errorMsg}</div>
                                <div class="small text-muted">Status: ${xhr.status} ${thrown || ''}</div>
                                <button onclick="window.location.reload()" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="ri-refresh-line"></i> Refresh Page
                                </button>
                            </td>
                        </tr>
                    `);
                },
                dataSrc: function(json) {
                    // If there's an error in the response, show it
                    if (json.error) {
                        console.error('Server error:', json.error);
                        return [];
                    }
                    return json.data || [];
                }
            },
            columns: [
                { data: 'date_requisitioned', name: 'date_requisitioned', orderable: true },
                { data: 'user', name: 'user', orderable: true },
                { 
                    data: 'status', 
                    name: 'status', 
                    orderable: false,
                    render: function(data, type, row) {
                        return data; // Already formatted HTML from server
                    }
                },
                { 
                    data: 'remarks', 
                    name: 'remarks', 
                    orderable: false,
                    render: function(data) {
                        return data || 'N/A';
                    }
                },
                { 
                    data: 'currency', 
                    name: 'currency',
                    render: function(data) {
                        return data || 'UGX';
                    }
                },
                { 
                    data: 'amount', 
                    name: 'amount',
                    render: function(data) {
                        return data || '0';
                    }
                },
                { 
                    data: 'paid', 
                    name: 'paid',
                    render: function(data) {
                        return data || '0';
                    }
                },
                { 
                    data: 'balance', 
                    name: 'balance', 
                    orderable: false,
                    render: function(data) {
                        return data || '0';
                    }
                },
                { 
                    data: 'actions', 
                    name: 'actions', 
                    orderable: false, 
                    searchable: false,
                    render: function(data) {
                        return data || '';
                    }
                }
            ],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'desc']],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            scrollX: true,
            autoWidth: false,
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search requisitions...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ requisitions",
                paginate: {
                    first: '<i class="ri-arrow-left-s-line"></i><i class="ri-arrow-left-s-line"></i>',
                    last: '<i class="ri-arrow-right-s-line"></i><i class="ri-arrow-right-s-line"></i>',
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>'
                },
                emptyTable: '<div class="text-center p-4"><i class="ri-file-list-3-line text-muted" style="font-size: 3rem;"></i><h5 class="mt-3">No Requisitions Found</h5><p class="text-muted">There are no requisitions to display at this time.</p></div>'
            },
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="ri-file-excel-2-line"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ri-file-pdf-line"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line"></i> Print',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            initComplete: function() {
                // Apply custom classes to rows
                $('#tabledata tbody tr').each(function() {
                    // Apply status badge class to elements with status indicators
                    $(this).find('td span.badge, td div[style*="border-radius: 20px"]').addClass('status-badge');

                    // Apply action button classes
                    $(this).find('td a.btn, td button.btn').addClass('action-btn');
                    $(this).find('td a.btn:contains("View"), td button.btn:contains("View")').addClass('view-btn');
                    $(this).find('td a.btn:contains("Edit"), td button.btn:contains("Edit")').addClass('edit-btn');
                });
            }
        });

        // Refresh button functionality
        document.getElementById('refresh-table').addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;

            // Refresh the page
            setTimeout(() => {
                window.location.reload();
            }, 500);
        });

        // Export functionality
        document.getElementById('export-excel').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-excel:first').click();
        });

        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-pdf:first').click();
        });

        document.getElementById('export-print').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-print:first').click();
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
            .dt-buttons { display: none !important; }

            /* Item details popup styles */
            .item-details-popup {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
                border: 1px solid rgba(0, 0, 0, 0.1);
                max-height: 300px;
                overflow-y: auto;
            }

            .item-details-popup ul {
                margin-bottom: 0.5rem;
            }

            .item-details-popup li {
                margin-bottom: 0.25rem;
            }
        `;
        document.head.appendChild(style);

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle item details popup
        document.querySelectorAll('.item-details-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event bubbling

                // Close all other popups
                document.querySelectorAll('.item-details-popup').forEach(popup => {
                    if (popup !== this.nextElementSibling) {
                        popup.classList.add('d-none');
                    }
                });

                // Toggle this popup
                const popup = this.nextElementSibling;
                popup.classList.toggle('d-none');
            });
        });

        // Close popups when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.item-details-popup') && !e.target.closest('.item-details-toggle')) {
                document.querySelectorAll('.item-details-popup').forEach(popup => {
                    popup.classList.add('d-none');
                });
            }
        });
    });
</script>
@endpush

@endsection

