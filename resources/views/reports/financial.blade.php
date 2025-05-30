@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Financial Reports</h4>
            <p class="text-muted small mb-0">Generate detailed financial reports for your business</p>
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
                <input type="hidden" name="report_type" value="financial">
                
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
                    
                    <!-- Account Filter -->
                    <div class="col-md-3">
                        <label for="account_id" class="form-label fw-semibold">Account</label>
                        <select name="account_id" id="account_id" class="form-select">
                            <option value="">All Accounts</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->currency }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Transaction Type Filter -->
                    <div class="col-md-3">
                        <label for="transaction_type" class="form-label fw-semibold">Transaction Type</label>
                        <select name="transaction_type" id="transaction_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                            <option value="transfer">Transfer</option>
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

    <!-- Financial Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Revenue Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-money-dollar-circle-line text-success fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Revenue</p>
                            <h4 class="fw-bold mb-0">{{ number_format($totalRevenue, 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Expenses Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="ri-funds-line text-danger fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Total Expenses</p>
                            <h4 class="fw-bold mb-0">{{ number_format($totalExpenses, 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Net Profit Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-line-chart-line text-primary fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Net Profit</p>
                            <h4 class="fw-bold mb-0">{{ number_format($netProfit, 0) }}</h4>
                            <div class="small {{ $netProfit > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $netProfit > 0 ? '+' : '' }}{{ number_format(($netProfit / max($totalRevenue, 1)) * 100, 1) }}% margin
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Balances -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="ri-bank-line me-4 text-primary"></i>Account Balances
            </h5>
        </div>
        <div class="card-body p-16">
            <div class="row">
                @foreach($accounts as $account)
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border">
                        <div class="card-body">
                            <h6 class="card-title">{{ $account->name }}</h6>
                            <p class="card-text text-muted small mb-1">{{ $account->currency }}</p>
                            <h5 class="fw-bold mb-0">{{ number_format($account->balance, 0) }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Expense Categories -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="ri-pie-chart-line me-4 text-primary"></i>Expense Categories
            </h5>
        </div>
        <div class="card-body p-16">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Particular</th>
                            <th class="text-end">Total Expenses</th>
                            <th class="text-end">% of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenseCategories as $category)
                        @php
                            // In a real implementation, you would calculate the actual expenses for each category
                            $categoryExpenses = rand(1000, 50000);
                            $percentage = $totalExpenses > 0 ? ($categoryExpenses / $totalExpenses) * 100 : 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $category->name }}</span>
                            </td>
                            <td>{{ $category->particular }}</td>
                            <td class="text-end">{{ number_format($categoryExpenses, 0) }}</td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <span class="me-4">{{ number_format($percentage, 1) }}%</span>
                                    <div class="progress" style="width: 100px; height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Export buttons
        document.getElementById('exportPdf').addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById('reportForm');
            const formData = new FormData(form);
            formData.append('format', 'pdf');
            
            // Create a temporary form for the export
            const exportForm = document.createElement('form');
            exportForm.method = 'GET';
            exportForm.action = '{{ route("reports.export", ["type" => "financial"]) }}';
            
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
            exportForm.action = '{{ route("reports.export", ["type" => "financial"]) }}';
            
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
</script>
@endpush
