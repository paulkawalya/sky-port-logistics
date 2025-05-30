@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Invoices</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Invoices</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Include invoice stats partial -->
            @include('partials.invoice-stats')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="ri-file-list-3-line me-2 text-primary"></i>Invoices
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('/invoices?filter=' . $filter) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="ri-refresh-line"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-download-line"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                                            <li><a class="dropdown-item" href="{{ url('/invoices/export/excel?filter=' . $filter) }}" target="_blank"><i class="ri-file-excel-line me-2 text-success"></i>Excel</a></li>
                                            <li><a class="dropdown-item" href="{{ url('/invoices/export/pdf?filter=' . $filter) }}" target="_blank"><i class="ri-file-pdf-line me-2 text-danger"></i>PDF</a></li>
                                            <li><a class="dropdown-item" href="{{ url('/invoices/export/csv?filter=' . $filter) }}" target="_blank"><i class="ri-file-text-line me-2 text-info"></i>CSV</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="printTable(); return false;"><i class="ri-printer-line me-2 text-dark"></i>Print</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter buttons -->
                            <div class="mb-4">
                                <div class="btn-group" role="group" aria-label="Invoice filters">
                                    <a href="{{ url('/invoices?filter=all') }}" class="btn {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
                                    <a href="{{ url('/invoices?filter=pending') }}" class="btn {{ $filter === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
                                    <a href="{{ url('/invoices?filter=partially_paid') }}" class="btn {{ $filter === 'partially_paid' ? 'btn-primary' : 'btn-outline-primary' }}">Partially Paid</a>
                                    <a href="{{ url('/invoices?filter=paid') }}" class="btn {{ $filter === 'paid' ? 'btn-primary' : 'btn-outline-primary' }}">Paid</a>
                                    <a href="{{ url('/invoices?filter=overdue') }}" class="btn {{ $filter === 'overdue' ? 'btn-primary' : 'btn-outline-primary' }}">Overdue</a>
                                </div>
                            </div>
                            
                            <!-- Search form -->
                            <div class="mb-4">
                                <form action="{{ url('/invoices') }}" method="GET" class="d-flex">
                                    <input type="hidden" name="filter" value="{{ $filter }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search by invoice number or customer name" value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i> Search</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="invoices-table">
                                    <thead>
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Client</th>
                                            <th>Date</th>
                                            <th class="text-end">Total Amount</th>
                                            <th class="text-end">Paid Amount</th>
                                            <th class="text-end">Balance</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($processedInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ url('/invoices/' . $invoice['id']) }}" class="text-primary text-decoration-underline">
                                                        {{ $invoice['invoice_number'] }}
                                                    </a>
                                                </td>
                                                <td>{{ $invoice['customer_name'] }}</td>
                                                <td>{{ $invoice['date_invoiced'] }}</td>
                                                <td class="text-end">{{ $invoice['amount'] }}</td>
                                                <td class="text-end">{{ $invoice['paid_amount'] }}</td>
                                                <td class="text-end">{{ $invoice['balance'] }}</td>
                                                <td>{!! $invoice['status'] !!}</td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ url('/invoices/' . $invoice['id']) }}" class="btn btn-sm btn-primary btn-action"><i class="ri-eye-line"></i></a>
                                                        <a href="{{ url('/invoices/download/' . $invoice['id']) }}" class="btn btn-sm btn-outline-secondary btn-action"><i class="ri-download-line"></i></a>
                                                        <a href="{{ url('/invoices/send/' . $invoice['id']) }}" class="btn btn-sm btn-outline-info btn-action"><i class="ri-mail-send-line"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No invoices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination with Bootstrap styling and limited page numbers -->
                            <div class="d-flex flex-column align-items-center mt-4">
                                <div class="text-muted mb-2">
                                    Showing {{ $invoices->firstItem() ?? 0 }} to {{ $invoices->lastItem() ?? 0 }} of {{ $invoices->total() }} results
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <!-- Previous Page Link -->
                                        @if ($invoices->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">&laquo; Previous</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $invoices->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                                            </li>
                                        @endif

                                        <!-- Pagination Elements - Limited with Ellipses -->
                                        @php
                                            $currentPage = $invoices->currentPage();
                                            $lastPage = $invoices->lastPage();
                                            $window = 2; // Number of pages to show on each side of current page
                                        @endphp
                                        
                                        <!-- First Page Link -->
                                        @if($currentPage > ($window + 2))
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $invoices->url(1) }}">1</a>
                                            </li>
                                            
                                            <!-- Left Ellipsis -->
                                            @if($currentPage > ($window + 3))
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                        @endif
                                        
                                        <!-- Window of Pages Around Current Page -->
                                        @for($i = max(1, $currentPage - $window); $i <= min($lastPage, $currentPage + $window); $i++)
                                            @if($i == $currentPage)
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $i }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $invoices->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor
                                        
                                        <!-- Right side pages -->
                                        @if($currentPage < $lastPage - $window - 1)
                                            <!-- Right Ellipsis -->
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                            
                                            <!-- Last Page Link -->
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $invoices->url($lastPage) }}">{{ $lastPage }}</a>
                                            </li>
                                        @elseif($currentPage < $lastPage - $window)
                                            <!-- Just Last Page without Ellipsis -->
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $invoices->url($lastPage) }}">{{ $lastPage }}</a>
                                            </li>
                                        @endif

                                        <!-- Next Page Link -->
                                        @if ($invoices->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $invoices->nextPageUrl() }}" rel="next">Next &raquo;</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">Next &raquo;</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print and search scripts -->
<script>
    // Print function
    function printTable() {
        var printContents = document.getElementById('invoices-table').outerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = '<html><head><title>Invoices</title>' +
            '<style>' +
            'body { font-family: Arial, sans-serif; padding: 20px; }' +
            'h2 { color: #333; margin-bottom: 20px; }' +
            'table { width: 100%; border-collapse: collapse; }' +
            'th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }' +
            'th { background-color: #f2f2f2; }' +
            '.text-end { text-align: right; }' +
            '.badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; }' +
            '.bg-success { background-color: #28a745; }' +
            '.bg-warning { background-color: #ffc107; color: #212529; }' +
            '.bg-danger { background-color: #dc3545; }' +
            '.btn { display: none; }' +
            '</style>' +
            '</head><body>' +
            '<h2>Invoices - {{ ucfirst($filter) }}</h2>' +
            printContents +
            '</body></html>';
            
        window.print();
        document.body.innerHTML = originalContents;
    }
    
    // Simple debounce function to limit how often the search is performed
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Wait for document to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Get search form and input
        const searchForm = document.querySelector('form[action*="/invoices"]');
        const searchInput = searchForm ? searchForm.querySelector('input[name="search"]') : null;
        
        if (searchForm && searchInput) {
            // Auto-submit the form after user stops typing for 500ms
            const debouncedSubmit = debounce(function() {
                searchForm.submit();
            }, 500);
            
            // Listen for input changes
            searchInput.addEventListener('input', function() {
                // Only auto-submit if there's text in the search box or if it was previously filled and now empty
                if (this.value.trim().length > 2 || (this.getAttribute('data-had-value') === 'true' && this.value.trim() === '')) {
                    debouncedSubmit();
                }
                
                // Track if the input had a value
                if (this.value.trim() !== '') {
                    this.setAttribute('data-had-value', 'true');
                }
            });
        }
    });
</script>
@endsection
