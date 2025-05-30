@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage and track all container shipments</p>
        </div>
        @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['create_containers']))
            <a href="{{ route('containers.create') }}" class="btn btn-primary">
                <i class="ri-add-line me-8"></i> Add New Container
            </a>
        @endif
    </div>

    <!-- Date Filter Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="ri-calendar-line me-4 text-primary"></i>Filter by Date Range</h5>
            <form id="date-filter-form">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-calendar-check-line text-muted"></i>
                            </span>
                            <input type="date" id="start-date" class="form-control border-start-0 ps-0" placeholder="Start Date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-calendar-check-line text-muted"></i>
                            </span>
                            <input type="date" id="end-date" class="form-control border-start-0 ps-0" placeholder="End Date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary px-8">
                            <i class="ri-filter-3-line me-8"></i> Apply Filter
                        </button>
                        <button type="reset" class="btn btn-outline-secondary ms-2">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Dashboard Overview -->
    <div class="row mb-4">
        <div class="col-md-7">
            <!-- Status Cards -->
            <div class="row g-3">
                <!-- On Port Card -->
                <div class="col-md-6 col-lg-4 mb-16">
                    <div class="card h-100 border-0 shadow-sm status-card" data-status="On Port">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background-color: rgba(13, 110, 253, 0.1);">
                                <i class="ri-ship-line fs-4 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="fs-4 fw-bold mb-0">{{ $onPortCount }}</h3>
                                <p class="text-muted small mb-0">On Port</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Picked Up Card -->
                <div class="col-md-6 col-lg-4 mb-16">
                    <div class="card h-100 border-0 shadow-sm status-card" data-status="Pickedup">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background-color: rgba(25, 135, 84, 0.1);">
                                <i class="ri-truck-line fs-4 text-success"></i>
                            </div>
                            <div>
                                <h3 class="fs-4 fw-bold mb-0">{{ $pickedUpCount }}</h3>
                                <p class="text-muted small mb-0">Picked Up</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Transit Card -->
                <div class="col-md-6 col-lg-4 mb-16">
                    <div class="card h-100 border-0 shadow-sm status-card" data-status="In Transit">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background-color: rgba(255, 193, 7, 0.1);">
                                <i class="ri-route-line fs-4 text-warning"></i>
                            </div>
                            <div>
                                <h3 class="fs-4 fw-bold mb-0">{{ $inTransitCount }}</h3>
                                <p class="text-muted small mb-0">In Transit</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Warehouse Card -->
                <div class="col-md-6 col-lg-4 mb-16">
                    <div class="card h-100 border-0 shadow-sm status-card" data-status="In Warehouse">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background-color: rgba(108, 117, 125, 0.1);">
                                <i class="ri-store-2-line fs-4 text-secondary"></i>
                            </div>
                            <div>
                                <h3 class="fs-4 fw-bold mb-0">{{ $inWarehouseCount }}</h3>
                                <p class="text-muted small mb-0">In Bond</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transferred Card -->
                <div class="col-md-6 col-lg-4 mb-16">
                    <div class="card h-100 border-0 shadow-sm status-card" data-status="Transferred">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background-color: rgba(220, 53, 69, 0.1);">
                                <i class="ri-exchange-box-line fs-4 text-danger"></i>
                            </div>
                            <div>
                                <h3 class="fs-4 fw-bold mb-0">{{ $transferredCount }}</h3>
                                <p class="text-muted small mb-0">Transferred</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Card -->
                <div class="col-md-6 col-lg-4 mb-16">
                    <div class="card h-100 border-0 shadow-sm status-card" data-status="All">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                 style="width: 48px; height: 48px; background-color: rgba(13, 202, 240, 0.1);">
                                <i class="ri-apps-line fs-4 text-info"></i>
                            </div>
                            <div>
                                <h3 class="fs-4 fw-bold mb-0">{{ $totalCount }}</h3>
                                <p class="text-muted small mb-0">Total Containers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="col-md-5 mb-16">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-3">
                    <h5 class="card-title mb-0"><i class="ri-pie-chart-line me-4 text-primary"></i>Container Status Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="status-chart" class="apex-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-inbox-archive-line me-4 text-primary"></i>Container List
                </h5>
                <div class="d-flex gap-4">
                    <button class="btn btn-sm btn-outline-primary" id="refresh-table">
                        <i class="ri-refresh-line me-8"></i> Refresh
                    </button>
                    <div class="dropdown">
                        <button class="custom-export-btn dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-download-line me-8"></i> Export
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
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabledata">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Container</th>
                            <th>Freight Value</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transits as $transit)
                            <tr>
                                <td data-order="{{ $transit->created_at->timestamp }}">
                                    <span>{{ $transit->created_at->format('M d, Y') }}</span>
                                    <span class="d-none">{{ $transit->created_at->timestamp }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 32px; height: 32px; border-radius: 6px; background-color: rgba(0, 99, 247, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                            <i class="ri-ship-line" style="color: #0063f7; font-size: 16px;"></i>
                                        </div>
                                        <a href="{{ route('containers.details', $transit->id) }}" class="fw-bold text-decoration-none">{{ $transit->number }}</a>
                                    </div>
                                </td>
                                <td>{{ $transit->entry_number }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $transit->category }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'In Transit' => ['bg-warning-subtle', 'text-warning'],
                                            'On Port' => ['bg-primary-subtle', 'text-primary'],
                                            'Pickedup' => ['bg-success-subtle', 'text-success'],
                                            'In Warehouse' => ['bg-secondary-subtle', 'text-secondary'],
                                            'Transferred' => ['bg-danger-subtle', 'text-danger']
                                        ];
                                        $status = $transit->status($transit->id);
                                        $colorClasses = $statusColors[$status] ?? ['bg-light', 'text-dark'];
                                    @endphp
                                    <span class="badge {{ $colorClasses[0] }} {{ $colorClasses[1] }} px-3 py-2 rounded-pill">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    @if($transit->payment_status == 'paid')
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Paid</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex">
                                        @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['view_containers']))
                                            <a href="{{ route('containers.show', $transit->id) }}" class="btn btn-sm" style="background-color: rgba(0, 99, 247, 0.1); color: #0063f7; border: none; margin-right: 8px; border-radius: 6px;">
                                                <i class="ri-eye-line me-8"></i> Details
                                            </a>
                                        @endif

                                        @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['edit_containers']))
                                            <a href="{{ route('containers.edit', $transit->id) }}" class="btn btn-sm" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d; border: none; border-radius: 6px;">
                                                <i class="ri-edit-line me-8"></i>
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

@include('layouts.container_data_tables')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable with advanced features
        const dataTable = $('#tabledata').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            dom: 'Blfrtip',
            deferRender: true,
            order: [[0, 'desc']], // Order by date column (first column) in descending order
            columnDefs: [
                { "type": "num", "targets": 0 } // Treat first column as numeric for sorting (timestamp)
            ],
            language: {
                search: "<i class='ri-search-line'></i>",
                searchPlaceholder: "Search containers...",
                lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                info: "Showing _START_ to _END_ of _TOTAL_ containers",
                paginate: {
                    first: "<i class='ri-arrow-left-s-line'></i><i class='ri-arrow-left-s-line'></i>",
                    last: "<i class='ri-arrow-right-s-line'></i><i class='ri-arrow-right-s-line'></i>",
                    next: "<i class='ri-arrow-right-s-line'></i>",
                    previous: "<i class='ri-arrow-left-s-line'></i>"
                },
                emptyTable: '<div style="padding: 30px; text-align: center;"><div style="width: 80px; height: 80px; border-radius: 50%; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;"><i class="ri-inbox-line" style="font-size: 40px; color: #adb5bd;"></i></div><h5 style="margin-bottom: 10px; color: #495057;">No Data Found</h5><p style="color: #6c757d; max-width: 300px; margin: 0 auto;">There are no records to display at this time.</p></div>'
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    messageTop: '{{$title}}',
                    className: 'excel-button',
                    text: '<i class="ri-file-excel-2-line"></i> Excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    messageTop: '{{$title}}',
                    className: 'pdf-button',
                    text: '<i class="ri-file-pdf-line"></i> PDF',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    className: 'csv-button',
                    text: '<i class="ri-file-text-line"></i> CSV',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            // Apply custom classes to status and action buttons
            createdRow: function(row, data, dataIndex) {
                // Apply status badge class to elements with status indicators
                $(row).find('td span.badge, td div[style*="border-radius: 20px"]').addClass('status-badge');

                // Apply action button classes
                $(row).find('td a.btn, td button.btn').addClass('action-btn');
                $(row).find('td a.btn:contains("View"), td button.btn:contains("View")').addClass('view-btn');
                $(row).find('td a.btn:contains("Edit"), td button.btn:contains("Edit")').addClass('edit-btn');
            }
        });

        // Initialize the chart with default data
        const chartColors = {
            'On Port': '#0d6efd',
            'Pickedup': '#198754',
            'In Transit': '#ffc107',
            'In Warehouse': '#6c757d',
            'Transferred': '#dc3545'
        };

        const chartOptions = {
            series: [{{ $statusCounts['On Port'] }}, {{ $statusCounts['Pickedup'] }}, {{ $statusCounts['In Transit'] }}, {{ $statusCounts['In Warehouse'] }}, {{ $statusCounts['Transferred'] }}],
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                toolbar: {
                    show: false
                }
            },
            labels: ['On Port', 'Pickedup', 'In Transit', 'In Warehouse', 'Transferred'],
            colors: Object.values(chartColors),
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                fontWeight: 600,
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                fontWeight: 700,
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                fontWeight: 600,
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '13px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 50
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            tooltip: {
                enabled: true,
                fillSeriesColor: false,
                theme: 'light',
                style: {
                    fontSize: '13px'
                },
                y: {
                    formatter: function(value) {
                        return value + ' containers';
                    }
                }
            },
            stroke: {
                width: 0
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 280
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        const chart = new ApexCharts(document.querySelector("#status-chart"), chartOptions);
        chart.render();

        // Add click event to status cards for filtering
        document.querySelectorAll('.status-card').forEach(card => {
            card.addEventListener('click', function() {
                const status = this.getAttribute('data-status');

                // Remove active class from all cards
                document.querySelectorAll('.status-card').forEach(c => {
                    c.classList.remove('active-card');
                });

                // Add active class to clicked card
                this.classList.add('active-card');

                // Filter the table using jQuery for compatibility with the existing DataTable
                if (status === 'All') {
                    // Clear the search and redraw
                    $('#tabledata').DataTable().search('').draw();
                } else {
                    // Apply the search filter and redraw
                    $('#tabledata').DataTable().search(status).draw();
                }

                // Highlight the corresponding chart segment
                const statusIndex = chartOptions.labels.indexOf(status);
                if (statusIndex !== -1) {
                    chart.toggleDataPointSelection(statusIndex);
                }
            });
        });

        // Chart click event for filtering
        chart.addEventListener('dataPointSelection', function(event, chartContext, config) {
            const status = config.w.config.labels[config.dataPointIndex];

            // Remove active class from all cards
            document.querySelectorAll('.status-card').forEach(card => {
                card.classList.remove('active-card');
            });

            // Add active class to corresponding card
            const card = document.querySelector(`.status-card[data-status="${status}"]`);
            if (card) card.classList.add('active-card');

            // Filter the table using jQuery for compatibility with the existing DataTable
            $('#tabledata').DataTable().search(status).draw();
        });

        // Date filter form submission
        document.getElementById('date-filter-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-4" role="status" aria-hidden="true"></span> Loading...';
            submitBtn.disabled = true;

            // Fetch filtered data
            fetch(`/containers/filter?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    if (data.statusCounts) {
                        // Update chart data
                        chart.updateSeries([
                            data.statusCounts['On Port'] || 0,
                            data.statusCounts['Pickedup'] || 0,
                            data.statusCounts['In Transit'] || 0,
                            data.statusCounts['In Warehouse'] || 0,
                            data.statusCounts['Transferred'] || 0
                        ]);

                        // Update card counts
                        updateCardCounts(data.statusCounts);

                        // Apply date filter to table
                        applyDateFilter(startDate, endDate);

                        // Show success message
                        showToast('Filter applied successfully', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    showToast('Error applying filter', 'error');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
        });

        // Reset filter button
        document.querySelector('button[type="reset"]').addEventListener('click', function() {
            // Clear date inputs
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';

            // Reset table filter using jQuery for compatibility with the existing DataTable
            $('#tabledata').DataTable().search('').columns().search('').draw();

            // Reset chart selection
            chart.resetSeries();

            // Remove active class from all cards
            document.querySelectorAll('.status-card').forEach(card => {
                card.classList.remove('active-card');
            });

            showToast('Filters reset successfully', 'info');
        });

        // Refresh table button
        document.getElementById('refresh-table').addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;

            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            btn.disabled = true;

            // Refresh the page to get fresh data
            setTimeout(() => {
                window.location.reload();
            }, 500);
        });

        // Helper function to update card counts
        function updateCardCounts(statusCounts) {
            // Update each status card with new count
            document.querySelector('.status-card[data-status="On Port"] .fs-4').textContent = statusCounts['On Port'] || 0;
            document.querySelector('.status-card[data-status="Pickedup"] .fs-4').textContent = statusCounts['Pickedup'] || 0;
            document.querySelector('.status-card[data-status="In Transit"] .fs-4').textContent = statusCounts['In Transit'] || 0;
            document.querySelector('.status-card[data-status="In Warehouse"] .fs-4').textContent = statusCounts['In Warehouse'] || 0;
            document.querySelector('.status-card[data-status="Transferred"] .fs-4').textContent = statusCounts['Transferred'] || 0;
            document.querySelector('.status-card[data-status="All"] .fs-4').textContent = statusCounts['Total'] || 0;
        }

        // Helper function to apply date filter to table
        function applyDateFilter(startDate, endDate) {
            // Custom filtering function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const rowDate = new Date(data[0]);
                const start = new Date(startDate);
                const end = new Date(endDate);

                // Add one day to end date to include the end date in the range
                end.setDate(end.getDate() + 1);

                return (rowDate >= start && rowDate < end);
            });

            // Redraw the table using jQuery for compatibility with the existing DataTable
            $('#tabledata').DataTable().draw();

            // Remove the filter after drawing
            $.fn.dataTable.ext.search.pop();
        }

        // Helper function to show toast notifications
        function showToast(message, type = 'info') {
            // Create toast container if it doesn't exist
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }

            // Set icon and background color based on type
            let icon, bgClass;
            switch(type) {
                case 'success':
                    icon = 'ri-check-line';
                    bgClass = 'bg-success';
                    break;
                case 'error':
                    icon = 'ri-error-warning-line';
                    bgClass = 'bg-danger';
                    break;
                case 'warning':
                    icon = 'ri-alert-line';
                    bgClass = 'bg-warning';
                    break;
                default:
                    icon = 'ri-information-line';
                    bgClass = 'bg-info';
            }

            // Create toast element
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="${icon} me-4"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-4 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            // Add toast to container
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);

            // Initialize and show the toast
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
            toast.show();

            // Remove toast after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }

        // Handle export buttons using DataTables Buttons extension
        document.getElementById('export-excel').addEventListener('click', function(e) {
            e.preventDefault();
            showToast('Exporting to Excel...', 'info');

            // Find and click the Excel export button
            setTimeout(() => {
                $('.excel-button').click();
                showToast('Excel export completed successfully', 'success');
            }, 500);
        });

        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            showToast('Generating PDF...', 'info');

            // Find and click the PDF export button
            setTimeout(() => {
                $('.pdf-button').click();
                showToast('PDF generated successfully', 'success');
            }, 500);
        });

        document.getElementById('export-csv').addEventListener('click', function(e) {
            e.preventDefault();
            showToast('Exporting to CSV...', 'info');

            // Find and click the CSV export button
            setTimeout(() => {
                $('.csv-button').click();
                showToast('CSV export completed successfully', 'success');
            }, 500);
        });

        document.getElementById('export-print').addEventListener('click', function(e) {
            e.preventDefault();
            showToast('Preparing print view...', 'info');

            // Use the browser's print functionality since there's no print button in the default setup
            setTimeout(() => {
                window.print();
            }, 500);
        });

        // Add CSS for active card state and other styling
        const style = document.createElement('style');
        style.textContent = `
            .status-card {
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .status-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            }
            .status-card.active-card {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
                border-left: 4px solid #0d6efd;
            }
            .toast-container {
                z-index: 1050;
            }
            /* Hide default DataTables buttons and show our custom ones */
            .dt-buttons {
                display: none !important;
            }

            /* Custom export button styling */
            .custom-export-btn {
                background-color: rgba(108, 117, 125, 0.1);
                color: #6c757d;
                border: none;
                border-radius: 6px;
                padding: 6px 12px;
                font-size: 13px;
                transition: all 0.3s ease;
            }

            .custom-export-btn:hover {
                background-color: rgba(108, 117, 125, 0.2);
                transform: translateY(-2px);
            }
            /* Print styles */
            @media print {
                .card-header, .btn, .dropdown, .status-card, .chart-container {
                    display: none !important;
                }
                .card {
                    border: none !important;
                    box-shadow: none !important;
                }
                .table {
                    width: 100% !important;
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
