@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="page-title mb-0">Reports Dashboard</h2>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-center">
                        <div class="col-md-4">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Today's Collection</h5>
                    <h3 class="text-primary">{{ number_format($amountCollectedToday, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Last 7 Days</h5>
                    <h3 class="text-primary">{{ number_format($amountCollectedLast7Days, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Custom Period</h5>
                    <h3 class="text-primary">{{ number_format($amountCollectedCustomPeriod, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Containers</h5>
                    <h3 class="text-warning">{{ $containerStats->in_transit }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Monthly Collections</h5>
                    <canvas id="monthlyCollectionsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Invoice Aging Distribution</h5>
                    <canvas id="agingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers and Popular Routes -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Customers</h5>
                    <div class="table-responsive">
                        <table class="table" id="topCustomersTable">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Revenue</th>
                                    <th>Invoices</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->customer->name }}</td>
                                    <td>{{ number_format($customer->total_revenue, 2) }}</td>
                                    <td>{{ $customer->invoice_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Popular Routes</h5>
                    <div class="table-responsive">
                        <table class="table" id="popularRoutesTable">
                            <thead>
                                <tr>
                                    <th>Route</th>
                                    <th>Count</th>
                                    <th>Avg Transit Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($popularRoutes as $route)
                                <tr>
                                    <td>{{ $route->origin }} → {{ $route->destination }}</td>
                                    <td>{{ $route->route_count }}</td>
                                    <td>{{ number_format($route->avg_transit_time, 1) }} days</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Aging Report -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Invoices Aging Report</h5>
                    <div class="table-responsive">
                        <table class="table" id="agingTable">
                            <thead>
                                <tr>
                                    <th>Aging Period</th>
                                    <th>Invoice Count</th>
                                    <th>Total Amount</th>
                                    <th>Amount Paid</th>
                                    <th>Outstanding</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agingReport as $aging)
                                <tr>
                                    <td>{{ $aging->aging_period }}</td>
                                    <td>{{ $aging->invoice_count }}</td>
                                    <td>{{ number_format($aging->total_amount, 2) }}</td>
                                    <td>{{ number_format($aging->amount_paid, 2) }}</td>
                                    <td>{{ number_format($aging->total_amount - $aging->amount_paid, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Containers in Transit -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Containers in Transit</h5>
                    <div class="table-responsive">
                        <table class="table" id="containersTable">
                            <thead>
                                <tr>
                                    <th>Container No</th>
                                    <th>Customer</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Status</th>
                                    <th>In Transit Since</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($containersInTransit as $container)
                                <tr>
                                    <td>{{ $container->container_no }}</td>
                                    <td>{{ $container->customer->name }}</td>
                                    <td>{{ $container->origin }}</td>
                                    <td>{{ $container->destination }}</td>
                                    <td>
                                        <span class="badge bg-warning">In Transit</span>
                                    </td>
                                    <td>{{ $container->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize DataTables
    $(document).ready(function() {
        $('#agingTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        $('#containersTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        $('#topCustomersTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        $('#popularRoutesTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
    });

    // Monthly Collections Chart
    const monthlyData = @json($monthlyCollections);
    new Chart(document.getElementById('monthlyCollectionsChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Collections',
                data: monthlyData.map(item => item.total_collected),
                borderColor: '#dc0000',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Aging Chart
    const agingData = @json($agingReport);
    new Chart(document.getElementById('agingChart'), {
        type: 'doughnut',
        data: {
            labels: agingData.map(item => item.aging_period),
            datasets: [{
                data: agingData.map(item => item.invoice_count),
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Export functions
    function exportToPDF() {
        window.print();
    }

    function exportToExcel() {
        // Trigger DataTables Excel export for the main aging table
        $('#agingTable').DataTable().button('2').trigger();
    }
</script>
@endpush

@push('styles')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        border: none;
        margin-bottom: 1rem;
    }
    .card-title {
        color: #666;
        font-size: 1rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .text-primary {
        color: #dc0000 !important;
    }
    .badge {
        padding: 0.5em 1em;
    }
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .dt-buttons {
        margin-bottom: 1rem;
    }
    .dt-button {
        background-color: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        padding: 0.375rem 0.75rem !important;
    }
    @media print {
        .btn-group, .dt-buttons {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
