@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage and track all agents in the system</p>
        </div>
        @if(Auth::user()->canDo(['create_agent']))
        <a href="{{ route('agents.create') }}" class="btn btn-primary">
            <i class="ri-user-add-line me-1"></i> Add Agent
        </a>
        @endif
    </div>

    <!-- Agents Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Agents Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-team-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($agents) }}</h5>
                            <p class="text-muted small mb-0">Total Agents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agents with Shipments -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-ship-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ \App\Models\Shippment::distinct('agent_id')->count('agent_id') }}</h5>
                            <p class="text-muted small mb-0">With Shipments</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New This Month -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-calendar-check-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ \App\Models\Agent::where('created_at', '>=', now()->startOfMonth())->count() }}</h5>
                            <p class="text-muted small mb-0">New This Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agents Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-team-line me-4 text-primary"></i>Agents List
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
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agents as $agent)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-4 bg-light d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <span class="fw-bold text-primary">{{ substr($agent->name, 0, 1) }}</span>
                                    </div>
                                    <span class="fw-semibold">{{ $agent->name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line me-4 text-muted"></i>
                                    <span>{{ $agent->phone_number }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-4">
                                    @if(Auth::user()->canDo(['view_agent_shipments']))
                                    <a href="{{ route('agents.show', $agent->id) }}" class="btn btn-sm btn-primary" title="View Shipments">
                                        <i class="ri-ship-line"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->canDo(['edit_agent']))
                                    <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-secondary" title="Edit Agent">
                                        <i class="ri-edit-line"></i>
                                    </a>
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
@endsection

@include('layouts.data_tables')

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with advanced features
        const dataTable = $('#tabledata').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            dom: '<"top"<"row"<"col-md-6"l><"col-md-6"f>><"row"<"col-md-6"B><"col-md-6"p>>>rt<"bottom"<"row"<"col-md-6"i><"col-md-6"p>>>',
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
                searchPlaceholder: "Search agents...",
                lengthMenu: "<span class='me-2'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ agents",
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
