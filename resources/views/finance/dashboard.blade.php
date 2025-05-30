@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Finance Dashboard</h4>
            <p class="text-muted small mb-0">Financial overview and analytics</p>
        </div>
        <div class="d-flex gap-4">
            <!-- Currency Selector -->
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-money-dollar-circle-line me-1"></i> Currency: {{ $selectedCurrency }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="currencyDropdown">
                    <li><a class="dropdown-item currency-selector {{ $selectedCurrency === 'all' ? 'active' : '' }}" href="{{ route('finance.dashboard', ['currency' => 'all']) }}">All Currencies</a></li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach($currencies as $currency)
                    <li><a class="dropdown-item currency-selector {{ $selectedCurrency === $currency ? 'active' : '' }}" href="{{ route('finance.dashboard', ['currency' => $currency]) }}">{{ $currency }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Period Selector -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-calendar-line me-1"></i> Period: Last 12 Months
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="periodDropdown">
                    <li><a class="dropdown-item period-selector" href="#" data-period="month">Last Month</a></li>
                    <li><a class="dropdown-item period-selector" href="#" data-period="quarter">Last Quarter</a></li>
                    <li><a class="dropdown-item period-selector active" href="#" data-period="year">Last 12 Months</a></li>
                </ul>
            </div>

            <!-- Export Options -->
            <div class="dropdown">
                <button class="btn btn-outline-danger dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-download-line me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="{{ route('finance.dashboard.export', ['format' => 'pdf', 'currency' => $selectedCurrency]) }}"><i class="ri-file-pdf-line me-4 text-danger"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('finance.dashboard.export', ['format' => 'excel', 'currency' => $selectedCurrency]) }}"><i class="ri-file-excel-line me-4 text-success"></i>Excel</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Currency Summary Cards -->
        @if($selectedCurrency === 'all')
            <div class="col-12 mb-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-exchange-dollar-line me-4 text-primary"></i>Currency Breakdown
                        </h5>
                    </div>
                    <div class="card-body p-16">
                        <div class="row g-3">
                            @foreach($currencies as $currency)
                                <div class="col-md-6">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light py-3">
                                            <h6 class="card-title mb-0 d-flex align-items-center">
                                                <i class="ri-money-dollar-circle-line me-4 text-primary"></i>{{ $currency }} Summary
                                                <a href="{{ route('finance.dashboard', ['currency' => $currency]) }}" class="btn btn-sm btn-outline-primary ms-auto">
                                                    View Details
                                                </a>
                                            </h6>
                                        </div>
                                        <div class="card-body p-16">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted small mb-0">Revenue</p>
                                                            <h5 class="fw-bold mb-0">{{ number_format($financialData[$currency]['revenue'], 0) }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background-color: rgba(220, 53, 69, 0.1);">
                                                            <i class="ri-funds-line text-danger"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted small mb-0">Expenses</p>
                                                            <h5 class="fw-bold mb-0">{{ number_format($financialData[$currency]['expenses'], 0) }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                                                            <i class="ri-line-chart-line text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-muted small mb-0">Profit</p>
                                                            <h5 class="fw-bold mb-0">{{ number_format($financialData[$currency]['profit'], 0) }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Total Revenue Card -->
        <div class="col-md-3">
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
                            <span class="badge bg-success-subtle">{{ $selectedCurrency }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expenses Card -->
        <div class="col-md-3">
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
                            <span class="badge bg-danger-subtle">{{ $selectedCurrency }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Profit Card -->
        <div class="col-md-3">
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
                            <span class="badge bg-primary-subtle">{{ $selectedCurrency }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Status Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="card-title mb-0">
                        <i class="ri-bill-line me-2 text-info"></i>Invoices
                    </h6>
                </div>
                <div class="card-body p-16">
                    <!-- Total Invoices -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-bill-line text-info"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">All Invoices</p>
                            <h5 class="fw-bold mb-0">{{ $totalInvoices }} <span class="small text-success">({{ $paidInvoices }} paid)</span></h5>
                            <span class="badge bg-info-subtle">{{ $pendingInvoices }} pending</span>
                        </div>
                    </div>

                    <!-- Regular Invoices -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-ship-line text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Shipment Invoices</p>
                            <h6 class="fw-bold mb-0">{{ $regularTotalInvoices }} <span class="small text-success">({{ $regularPaidInvoices }} paid)</span></h6>
                            <span class="badge bg-primary-subtle">{{ $regularPendingInvoices }} pending</span>
                        </div>
                    </div>

                    <!-- Manual Invoices -->
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(255, 193, 7, 0.1);">
                            <i class="ri-file-list-3-line text-warning"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Manual Invoices</p>
                            <h6 class="fw-bold mb-0">{{ $manualTotalInvoices }} <span class="small text-success">({{ $manualPaidInvoices }} paid)</span></h6>
                            <span class="badge bg-warning-subtle">{{ $manualPendingInvoices }} pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <!-- Revenue & Expenses Chart -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-line-chart-line me-4 text-primary"></i>Revenue & Expenses
                    </h5>
                </div>
                <div class="card-body p-16">
                    <div id="revenueExpensesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Top Expense Categories Chart -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-pie-chart-line me-4 text-primary"></i>Expense Breakdown
                    </h5>
                </div>
                <div class="card-body p-16">
                    <div id="expenseCategoriesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Status Section -->
    <div class="row g-3 mb-4">
        <!-- Payables (Pending Invoices) -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-time-line me-2 text-warning"></i>Pending Invoices (Payables)
                        </h5>
                        <span class="badge bg-warning-subtle px-3 py-2">{{ $pendingInvoices }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs" id="pendingInvoicesTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-shipment-tab" data-bs-toggle="tab" data-bs-target="#pending-shipment" type="button" role="tab" aria-controls="pending-shipment" aria-selected="true">
                                Shipment ({{ $regularPendingInvoices }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-manual-tab" data-bs-toggle="tab" data-bs-target="#pending-manual" type="button" role="tab" aria-controls="pending-manual" aria-selected="false">
                                Manual ({{ $manualPendingInvoices }})
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content p-0" id="pendingInvoicesTabContent">
                        <div class="tab-pane fade show active" id="pending-shipment" role="tabpanel" aria-labelledby="pending-shipment-tab">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pendingShipmentInvoicesTable">
                                        <!-- Will be loaded via AJAX -->
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span class="ms-2">Loading pending shipment invoices...</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pending-manual" role="tabpanel" aria-labelledby="pending-manual-tab">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pendingManualInvoicesTable">
                                        <!-- Will be loaded via AJAX -->
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span class="ms-2">Loading pending manual invoices...</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Invoices -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-checkbox-circle-line me-2 text-success"></i>Paid Invoices
                        </h5>
                        <span class="badge bg-success-subtle px-3 py-2">{{ $paidInvoices }} Total</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-tabs" id="paidInvoicesTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="paid-shipment-tab" data-bs-toggle="tab" data-bs-target="#paid-shipment" type="button" role="tab" aria-controls="paid-shipment" aria-selected="true">
                                Shipment ({{ $regularPaidInvoices }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="paid-manual-tab" data-bs-toggle="tab" data-bs-target="#paid-manual" type="button" role="tab" aria-controls="paid-manual" aria-selected="false">
                                Manual ({{ $manualPaidInvoices }})
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content p-0" id="paidInvoicesTabContent">
                        <div class="tab-pane fade show active" id="paid-shipment" role="tabpanel" aria-labelledby="paid-shipment-tab">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paidShipmentInvoicesTable">
                                        <!-- Will be loaded via AJAX -->
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span class="ms-2">Loading paid shipment invoices...</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paid-manual" role="tabpanel" aria-labelledby="paid-manual-tab">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paidManualInvoicesTable">
                                        <!-- Will be loaded via AJAX -->
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <span class="ms-2">Loading paid manual invoices...</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Balances & Recent Transactions -->
    <div class="row g-3">
        <!-- Account Balances -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-bank-line me-4 text-primary"></i>Account Balances
                        </h5>
                        @if($selectedCurrency !== 'all')
                            <span class="badge bg-primary-subtle px-3 py-2">{{ $selectedCurrency }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-16">
                    @if($accountBalances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Account</th>
                                        <th>Currency</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accountBalances as $account)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $account->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $account->currency === 'USD' ? 'bg-success-subtle' : 'bg-primary-subtle' }}">
                                                {{ $account->currency }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($account->balance, 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border-start border-4 border-info">
                            <div class="d-flex">
                                <i class="ri-information-line fs-4 me-4 text-info"></i>
                                <p class="mb-0">No accounts found for the selected currency.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-exchange-dollar-line me-4 text-primary"></i>Recent Transactions
                        </h5>
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-16">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="recentTransactionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Transactions will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
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
        // Initialize charts with default data
        initCharts({
            revenue: {
                labels: @json($monthlyRevenue['labels']),
                values: @json($monthlyRevenue['values'])
            },
            expenses: {
                labels: @json($monthlyExpenses['labels']),
                values: @json($monthlyExpenses['values'])
            },
            profit: {
                labels: @json($monthlyProfit['labels']),
                values: @json($monthlyProfit['values'])
            }
        });

        // Load recent transactions
        loadRecentTransactions();

        // Load invoice data
        loadInvoiceData();

        // Get current currency from URL, default to USD
        const urlParams = new URLSearchParams(window.location.search);
        const currentCurrency = urlParams.get('currency') || 'USD';

        // Period selector
        document.querySelectorAll('.period-selector').forEach(function(selector) {
            selector.addEventListener('click', function(e) {
                e.preventDefault();

                // Update active state
                document.querySelectorAll('.period-selector').forEach(item => item.classList.remove('active'));
                this.classList.add('active');

                // Update dropdown button text
                const period = this.dataset.period;
                let periodText = 'Last 12 Months';

                if (period === 'month') {
                    periodText = 'Last Month';
                } else if (period === 'quarter') {
                    periodText = 'Last Quarter';
                }

                document.getElementById('periodDropdown').innerHTML = `<i class="ri-calendar-line me-1"></i> Period: ${periodText}`;

                // Load data for the selected period, maintaining the currency parameter
                loadDashboardData(period, currentCurrency);
            });
        });
    });

    function initCharts(data) {
        // Revenue & Expenses Chart
        const revenueExpensesOptions = {
            series: [
                {
                    name: 'Revenue',
                    data: data.revenue.values,
                    color: '#198754'
                },
                {
                    name: 'Expenses',
                    data: data.expenses.values,
                    color: '#dc3545'
                },
                {
                    name: 'Profit',
                    data: data.profit.values,
                    color: '#0d6efd'
                }
            ],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: data.revenue.labels,
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
            }
        };

        const revenueExpensesChart = new ApexCharts(document.querySelector("#revenueExpensesChart"), revenueExpensesOptions);
        revenueExpensesChart.render();

        // Expense Categories Chart (placeholder - will be populated via AJAX)
        const expenseCategoriesOptions = {
            series: [44, 55, 13, 43, 22],
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'inherit'
            },
            labels: ['Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5'],
            colors: ['#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0'],
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

        const expenseCategoriesChart = new ApexCharts(document.querySelector("#expenseCategoriesChart"), expenseCategoriesOptions);
        expenseCategoriesChart.render();

        // Store chart instances for later updates
        window.dashboardCharts = {
            revenueExpenses: revenueExpensesChart,
            expenseCategories: expenseCategoriesChart
        };
    }

    function loadDashboardData(period, currency = 'USD') {
        // Show loading state
        document.querySelectorAll('.card-body').forEach(card => {
            card.classList.add('loading');
        });

        // Fetch data from the server with currency parameter
        fetch(`{{ route('finance.dashboard.data') }}?period=${period}&currency=${currency}`)
            .then(response => response.json())
            .then(data => {
                // Update Revenue & Expenses Chart
                window.dashboardCharts.revenueExpenses.updateOptions({
                    series: [
                        {
                            name: 'Revenue',
                            data: data.revenue.values
                        },
                        {
                            name: 'Expenses',
                            data: data.expenses.values
                        },
                        {
                            name: 'Profit',
                            data: data.profit.values
                        }
                    ],
                    xaxis: {
                        categories: data.revenue.labels
                    },
                    title: {
                        text: currency,
                        align: 'right',
                        style: {
                            fontSize: '12px',
                            fontWeight: 'normal',
                            color: '#9699a2'
                        }
                    }
                });

                // Update Expense Categories Chart
                window.dashboardCharts.expenseCategories.updateOptions({
                    series: data.topExpenseCategories.values,
                    labels: data.topExpenseCategories.labels,
                    title: {
                        text: currency,
                        align: 'right',
                        style: {
                            fontSize: '12px',
                            fontWeight: 'normal',
                            color: '#9699a2'
                        }
                    }
                });

                // Update Recent Transactions
                updateRecentTransactions(data.recentTransactions);

                // Update summary cards if provided
                if (data.summaryData) {
                    updateSummaryCards(data.summaryData);
                }

                // Remove loading state
                document.querySelectorAll('.card-body').forEach(card => {
                    card.classList.remove('loading');
                });
            })
            .catch(error => {
                console.error('Error loading dashboard data:', error);
                // Remove loading state
                document.querySelectorAll('.card-body').forEach(card => {
                    card.classList.remove('loading');
                });
            });
    }

    function updateSummaryCards(data) {
        // Update summary cards with new data
        if (data.totalRevenue) {
            document.querySelector('.revenue-value').textContent = new Intl.NumberFormat().format(data.totalRevenue);
        }
        if (data.totalExpenses) {
            document.querySelector('.expenses-value').textContent = new Intl.NumberFormat().format(data.totalExpenses);
        }
        if (data.netProfit) {
            document.querySelector('.profit-value').textContent = new Intl.NumberFormat().format(data.netProfit);
        }
    }

    function loadRecentTransactions() {
        fetch('/api/finance/recent-transactions')
            .then(response => response.json())
            .then(data => {
                updateRecentTransactions(data);
            })
            .catch(error => {
                console.error('Error loading recent transactions:', error);
                document.querySelector('#recentTransactionsTable tbody').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <i class="ri-error-warning-line fs-4 me-4"></i>
                                    <p class="mb-0">Error loading transactions. Please try again later.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
    }

    function loadInvoiceData() {
        // Get current currency from URL, default to USD
        const urlParams = new URLSearchParams(window.location.search);
        const currentCurrency = urlParams.get('currency') || 'USD';

        // Load regular invoices (pending)
        fetch(`/api/finance/invoices?status=pending&type=regular&currency=${currentCurrency}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#pendingShipmentInvoicesTable');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="alert alert-light border-start border-4 border-info mb-0">
                                    <div class="d-flex">
                                        <i class="ri-information-line fs-4 me-4 text-info"></i>
                                        <p class="mb-0">No pending shipment invoices found.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(invoice => {
                    const row = document.createElement('tr');

                    // Format date
                    const date = new Date(invoice.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    row.innerHTML = `
                        <td>
                            <span class="badge bg-primary-subtle">#${String(invoice.id).padStart(4, '0')}</span>
                        </td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="fw-medium">${invoice.customer ? invoice.customer.name : 'N/A'}</div>
                        </td>
                        <td class="text-end fw-semibold">
                            ${invoice.amount} ${invoice.currency}
                        </td>
                        <td class="text-center">
                            <a href="/invoices/${invoice.id}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading pending shipment invoices:', error);
                document.querySelector('#pendingShipmentInvoicesTable').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <i class="ri-error-warning-line fs-4 me-4"></i>
                                    <p class="mb-0">Error loading invoices. Please try again later.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });

        // Load manual invoices (pending)
        fetch(`/api/finance/quotations?status=pending&type=invoice&currency=${currentCurrency}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#pendingManualInvoicesTable');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="alert alert-light border-start border-4 border-info mb-0">
                                    <div class="d-flex">
                                        <i class="ri-information-line fs-4 me-4 text-info"></i>
                                        <p class="mb-0">No pending manual invoices found.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(invoice => {
                    const row = document.createElement('tr');

                    // Format date
                    const date = new Date(invoice.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    row.innerHTML = `
                        <td>
                            <span class="badge bg-warning-subtle">#${String(invoice.id).padStart(4, '0')}</span>
                        </td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="fw-medium">${invoice.customer ? invoice.customer.name : 'N/A'}</div>
                        </td>
                        <td class="text-end fw-semibold">
                            ${invoice.total_amount} ${invoice.transaction_currency}
                        </td>
                        <td class="text-center">
                            <a href="/quotations/${invoice.id}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading pending manual invoices:', error);
                document.querySelector('#pendingManualInvoicesTable').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <i class="ri-error-warning-line fs-4 me-4"></i>
                                    <p class="mb-0">Error loading invoices. Please try again later.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });

        // Load regular invoices (paid)
        fetch(`/api/finance/invoices?status=paid&type=regular&currency=${currentCurrency}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#paidShipmentInvoicesTable');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="alert alert-light border-start border-4 border-info mb-0">
                                    <div class="d-flex">
                                        <i class="ri-information-line fs-4 me-4 text-info"></i>
                                        <p class="mb-0">No paid shipment invoices found.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(invoice => {
                    const row = document.createElement('tr');

                    // Format date
                    const date = new Date(invoice.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    row.innerHTML = `
                        <td>
                            <span class="badge bg-primary-subtle">#${String(invoice.id).padStart(4, '0')}</span>
                        </td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="fw-medium">${invoice.customer ? invoice.customer.name : 'N/A'}</div>
                        </td>
                        <td class="text-end fw-semibold">
                            ${invoice.amount} ${invoice.currency}
                        </td>
                        <td class="text-center">
                            <a href="/invoices/${invoice.id}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading paid shipment invoices:', error);
                document.querySelector('#paidShipmentInvoicesTable').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <i class="ri-error-warning-line fs-4 me-4"></i>
                                    <p class="mb-0">Error loading invoices. Please try again later.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });

        // Load manual invoices (paid)
        fetch(`/api/finance/quotations?status=paid&type=invoice&currency=${currentCurrency}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#paidManualInvoicesTable');
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="alert alert-light border-start border-4 border-info mb-0">
                                    <div class="d-flex">
                                        <i class="ri-information-line fs-4 me-4 text-info"></i>
                                        <p class="mb-0">No paid manual invoices found.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(invoice => {
                    const row = document.createElement('tr');

                    // Format date
                    const date = new Date(invoice.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });

                    row.innerHTML = `
                        <td>
                            <span class="badge bg-warning-subtle">#${String(invoice.id).padStart(4, '0')}</span>
                        </td>
                        <td>${formattedDate}</td>
                        <td>
                            <div class="fw-medium">${invoice.customer ? invoice.customer.name : 'N/A'}</div>
                        </td>
                        <td class="text-end fw-semibold">
                            ${invoice.total_amount} ${invoice.transaction_currency}
                        </td>
                        <td class="text-center">
                            <a href="/quotations/${invoice.id}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error loading paid manual invoices:', error);
                document.querySelector('#paidManualInvoicesTable').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <i class="ri-error-warning-line fs-4 me-4"></i>
                                    <p class="mb-0">Error loading invoices. Please try again later.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            });
    }

    function updateRecentTransactions(transactions) {
        const tableBody = document.querySelector('#recentTransactionsTable tbody');
        tableBody.innerHTML = '';

        transactions.forEach(transaction => {
            const row = document.createElement('tr');

            // Format date
            const date = new Date(transaction.date);
            const formattedDate = date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });

            // Determine type class
            let typeClass = 'bg-secondary';
            if (transaction.type === 'Income') {
                typeClass = 'bg-success';
            } else if (transaction.type === 'Expense') {
                typeClass = 'bg-danger';
            } else if (transaction.type === 'Transfer') {
                typeClass = 'bg-info';
            }

            // Format amount
            const amount = new Intl.NumberFormat().format(Math.abs(transaction.amount));
            const amountClass = transaction.amount >= 0 ? 'text-success' : 'text-danger';
            const amountPrefix = transaction.amount >= 0 ? '+' : '-';

            row.innerHTML = `
                <td>${formattedDate}</td>
                <td>${transaction.description}</td>
                <td>${transaction.account}</td>
                <td><span class="badge ${typeClass}">${transaction.type}</span></td>
                <td class="${amountClass} fw-bold">${amountPrefix} ${amount}</td>
            `;

            tableBody.appendChild(row);
        });
    }
</script>
@endpush
