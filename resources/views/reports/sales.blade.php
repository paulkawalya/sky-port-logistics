@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sales Reports</h4>
            <p class="text-muted small mb-0">Generate detailed sales reports for your business</p>
        </div>
        <a href="{{ route('reports.dashboard') }}" class="btn btn-outline-secondary">
            <i class="ri-dashboard-line me-1"></i> Reports Dashboard
        </a>
    </div>

    <!-- Report Filters Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="ri-filter-3-line me-4 text-primary"></i>Report Filters
            </h5>
        </div>
        <div class="card-body p-16">
            <form action="{{ route('reports.generate') }}" method="POST" id="reportForm">
                @csrf
                <input type="hidden" name="report_type" value="sales">
                
                <div class="row g-3">
                    <!-- Date Range -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Date Range</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ri-calendar-line text-muted"></i>
                            </span>
                            <input type="date" name="start_date" class="form-control border-start-0 ps-0" value="{{ $startDate->format('Y-m-d') }}">
                            <span class="input-group-text bg-light border-start-0 border-end-0">to</span>
                            <input type="date" name="end_date" class="form-control border-start-0 ps-0" value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <!-- Customer Filter -->
                    <div class="col-md-3">
                        <label for="customer_id" class="form-label fw-semibold">Customer</label>
                        <select name="customer_id" id="customer_id" class="form-select">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Payment Status Filter -->
                    <div class="col-md-3">
                        <label for="payment_status" class="form-label fw-semibold">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="paid">Paid</option>
                            <option value="not paid">Not Paid</option>
                        </select>
                    </div>
                    
                    <!-- Generate Button -->
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <div class="dropdown me-4">
                            <button class="btn btn-outline-danger dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-download-line me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                                <li><a class="dropdown-item" href="#" id="exportPdf"><i class="ri-file-pdf-line me-4 text-danger"></i>PDF</a></li>
                                <li><a class="dropdown-item" href="#" id="exportExcel"><i class="ri-file-excel-line me-4 text-success"></i>Excel</a></li>
                            </ul>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-file-chart-line me-1"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Invoices Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-file-list-3-line text-primary fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Invoices</p>
                            <h4 class="fw-bold mb-0">{{ $totalInvoices }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paid Invoices Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-checkbox-circle-line text-success fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Paid Invoices</p>
                            <h4 class="fw-bold mb-0">{{ $paidInvoices }}</h4>
                            <div class="small text-success">
                                {{ $totalInvoices > 0 ? number_format(($paidInvoices / $totalInvoices) * 100, 1) : 0 }}% of total
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Invoices Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(255, 193, 7, 0.1);">
                            <i class="ri-time-line text-warning fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Pending Invoices</p>
                            <h4 class="fw-bold mb-0">{{ $pendingInvoices }}</h4>
                            <div class="small text-warning">
                                {{ $totalInvoices > 0 ? number_format(($pendingInvoices / $totalInvoices) * 100, 1) : 0 }}% of total
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Collection Rate Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-percent-line text-info fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Collection Rate</p>
                            <h4 class="fw-bold mb-0">{{ $totalSales > 0 ? number_format(($totalCollected / $totalSales) * 100, 1) : 0 }}%</h4>
                            <div class="small text-muted">
                                {{ number_format($totalCollected, 0) }} / {{ number_format($totalSales, 0) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="ri-user-star-line me-4 text-primary"></i>Top Customers
            </h5>
        </div>
        <div class="card-body p-16">
            <div class="row">
                @if(count($topCustomers) > 0)
                    @foreach($topCustomers as $customer)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title mb-0">{{ $customer->customer->name }}</h6>
                                    <span class="badge bg-primary">{{ $customer->invoice_count }} invoices</span>
                                </div>
                                <p class="card-text text-muted small mb-1">Total Sales</p>
                                <h5 class="fw-bold mb-0">{{ number_format($customer->total_amount, 0) }}</h5>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-info mb-0">
                            <i class="ri-information-line me-4"></i>No customer data available for the selected period.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="ri-line-chart-line me-4 text-primary"></i>Monthly Sales
            </h5>
        </div>
        <div class="card-body p-16">
            <div id="monthlySalesChart" style="height: 350px;"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.css">
<style>
    .apexcharts-tooltip {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 10px;
        border: none;
    }
    
    .apexcharts-tooltip-title {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 5px 10px;
        margin-bottom: 5px;
    }
    
    .apexcharts-xaxistooltip {
        background: #fff;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Monthly Sales Chart
        initMonthlySalesChart();
        
        // Export buttons
        document.getElementById('exportPdf').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('reportForm');
            const formData = new FormData(form);
            formData.append('format', 'pdf');
            
            // Create a temporary form for the export
            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = '{{ route("reports.export", ["type" => "sales"]) }}';
            
            // Add form data to the export form
            for (const [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                exportForm.appendChild(input);
            }
            
            document.body.appendChild(exportForm);
            exportForm.submit();
            document.body.removeChild(exportForm);
        });
        
        document.getElementById('exportExcel').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('reportForm');
            const formData = new FormData(form);
            formData.append('format', 'excel');
            
            // Create a temporary form for the export
            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = '{{ route("reports.export", ["type" => "sales"]) }}';
            
            // Add form data to the export form
            for (const [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                exportForm.appendChild(input);
            }
            
            document.body.appendChild(exportForm);
            exportForm.submit();
            document.body.removeChild(exportForm);
        });
    });
    
    function initMonthlySalesChart() {
        // Sample data for the chart
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const salesData = [65000, 59000, 80000, 81000, 56000, 55000, 40000, 45000, 70000, 85000, 90000, 100000];
        const collectedData = [62000, 55000, 78000, 75000, 50000, 52000, 38000, 42000, 65000, 80000, 85000, 95000];
        
        const options = {
            series: [
                {
                    name: 'Total Sales',
                    data: salesData,
                    color: '#0d6efd'
                },
                {
                    name: 'Amount Collected',
                    data: collectedData,
                    color: '#198754'
                }
            ],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadius: 4
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: months,
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return new Intl.NumberFormat().format(value);
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat().format(value);
                    }
                }
            },
            legend: {
                position: 'top'
            },
            grid: {
                borderColor: '#e9ecef',
                strokeDashArray: 4
            }
        };
        
        const chart = new ApexCharts(document.querySelector("#monthlySalesChart"), options);
        chart.render();
    }
</script>
@endpush
