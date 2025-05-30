@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Expense Reports</h4>
            <p class="text-muted small mb-0">Generate detailed expense reports for your business</p>
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
                <input type="hidden" name="report_type" value="expenses">
                
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
                    
                    <!-- Category Filter -->
                    <div class="col-md-6">
                        <label for="expense_category_id" class="form-label fw-semibold">Expense Category</label>
                        <select name="expense_category_id" id="expense_category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
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

    <!-- Total Expenses Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-16">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 64px; height: 64px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="ri-funds-line text-danger fs-2"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Expenses</p>
                            <h2 class="fw-bold mb-0">{{ number_format($totalExpenses, 0) }}</h2>
                            <p class="text-muted small mb-0">For period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="expenseTrendChart" style="height: 100px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Categories Chart -->
    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-pie-chart-line me-4 text-primary"></i>Expenses by Category
                    </h5>
                </div>
                <div class="card-body p-16">
                    <div id="expenseCategoriesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-bar-chart-grouped-line me-4 text-primary"></i>Category Breakdown
                    </h5>
                </div>
                <div class="card-body p-16">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Sample data for category breakdown
                                    $categories = [
                                        ['name' => 'Office Supplies', 'amount' => 12500, 'color' => '#4361ee'],
                                        ['name' => 'Rent', 'amount' => 45000, 'color' => '#3a0ca3'],
                                        ['name' => 'Utilities', 'amount' => 8500, 'color' => '#7209b7'],
                                        ['name' => 'Salaries', 'amount' => 85000, 'color' => '#f72585'],
                                        ['name' => 'Marketing', 'amount' => 15000, 'color' => '#4cc9f0']
                                    ];
                                    $totalAmount = array_sum(array_column($categories, 'amount'));
                                @endphp
                                
                                @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="color-indicator me-4" style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $category['color'] }};"></div>
                                            <span>{{ $category['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($category['amount'], 0) }}</td>
                                    <td class="text-end">{{ number_format(($category['amount'] / $totalAmount) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-time-line me-4 text-primary"></i>Recent Expenses
                </h5>
                <a href="{{ route('expense_categories.index') }}" class="btn btn-sm btn-outline-primary">
                    Manage Categories
                </a>
            </div>
        </div>
        <div class="card-body p-16">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Reference</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Sample data for recent expenses
                            $recentExpenses = [
                                ['date' => '2023-07-10', 'category' => 'Office Supplies', 'description' => 'Printer Ink and Paper', 'reference' => 'INV-2023-001', 'amount' => 2500],
                                ['date' => '2023-07-08', 'category' => 'Rent', 'description' => 'Office Rent for July', 'reference' => 'RENT-JUL-2023', 'amount' => 15000],
                                ['date' => '2023-07-05', 'category' => 'Utilities', 'description' => 'Electricity Bill', 'reference' => 'ELEC-JUL-2023', 'amount' => 3500],
                                ['date' => '2023-07-03', 'category' => 'Marketing', 'description' => 'Facebook Ads', 'reference' => 'FB-ADS-JUL-01', 'amount' => 5000],
                                ['date' => '2023-07-01', 'category' => 'Salaries', 'description' => 'Staff Salaries for June', 'reference' => 'SAL-JUN-2023', 'amount' => 85000]
                            ];
                        @endphp
                        
                        @foreach($recentExpenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense['date'])->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $expense['category'] }}</span>
                            </td>
                            <td>{{ $expense['description'] }}</td>
                            <td>
                                <span class="text-muted">{{ $expense['reference'] }}</span>
                            </td>
                            <td class="text-end fw-bold">{{ number_format($expense['amount'], 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
        // Initialize charts
        initExpenseTrendChart();
        initExpenseCategoriesChart();
        
        // Export buttons
        document.getElementById('exportPdf').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('reportForm');
            const formData = new FormData(form);
            formData.append('format', 'pdf');
            
            // Create a temporary form for the export
            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = '{{ route("reports.export", ["type" => "expenses"]) }}';
            
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
            exportForm.action = '{{ route("reports.export", ["type" => "expenses"]) }}';
            
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
    
    function initExpenseTrendChart() {
        // Sample data for expense trend
        const data = [8500, 9200, 10500, 9800, 11200, 12500, 11800, 13000, 14500, 13800, 15000, 16500];
        
        const options = {
            series: [{
                name: 'Expenses',
                data: data,
                color: '#dc3545'
            }],
            chart: {
                type: 'area',
                height: 100,
                sparkline: {
                    enabled: true
                },
                fontFamily: 'inherit'
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            tooltip: {
                fixed: {
                    enabled: false
                },
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function (seriesName) {
                            return '';
                        }
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat().format(value);
                    }
                },
                marker: {
                    show: false
                }
            }
        };
        
        const chart = new ApexCharts(document.querySelector("#expenseTrendChart"), options);
        chart.render();
    }
    
    function initExpenseCategoriesChart() {
        // Sample data for expense categories
        const categories = [
            { name: 'Office Supplies', amount: 12500, color: '#4361ee' },
            { name: 'Rent', amount: 45000, color: '#3a0ca3' },
            { name: 'Utilities', amount: 8500, color: '#7209b7' },
            { name: 'Salaries', amount: 85000, color: '#f72585' },
            { name: 'Marketing', amount: 15000, color: '#4cc9f0' }
        ];
        
        const options = {
            series: categories.map(cat => cat.amount),
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'inherit'
            },
            labels: categories.map(cat => cat.name),
            colors: categories.map(cat => cat.color),
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return new Intl.NumberFormat().format(value);
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '50%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Expenses',
                                formatter: function(w) {
                                    return new Intl.NumberFormat().format(w.globals.seriesTotals.reduce((a, b) => a + b, 0));
                                }
                            }
                        }
                    }
                }
            }
        };
        
        const chart = new ApexCharts(document.querySelector("#expenseCategoriesChart"), options);
        chart.render();
    }
</script>
@endpush
