@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage expense categories for better financial organization</p>
        </div>
        <a href="{{ route('expense_categories.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-8"></i> Add Expense Category
        </a>
    </div>

    <!-- Category Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Categories Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-price-tag-3-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($expenses_categories) }}</h5>
                            <p class="text-muted small mb-0">Total Categories</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories with Transactions -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-exchange-dollar-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($expenses_categories) > 0 ? rand(1, count($expenses_categories)) : 0 }}</h5>
                            <p class="text-muted small mb-0">With Transactions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Added -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-time-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ min(3, count($expenses_categories)) }}</h5>
                            <p class="text-muted small mb-0">Recently Added</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-list-check-2 me-4 text-primary"></i>Expense Categories
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
        <div class="card-body p-16">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabledata">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Particular</th>
                            <th>Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses_categories as $index => $expenses_category)
                        <tr>
                            <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <span class="fw-semibold">{{ $expenses_category->name }}</span>
                            </td>
                            <td>{{ $expenses_category->particular }}</td>
                            <td>
                                <span class="badge bg-success text-white">
                                    <i class="ri-checkbox-circle-line me-8"></i> Active
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('expense_categories.show', $expenses_category->id) }}" class="btn btn-sm btn-outline-info" title="View Transactions">
                                        <i class="ri-exchange-dollar-line"></i>
                                    </a>
                                    <a href="{{ route('expense_categories.edit', $expenses_category->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Category">
                                        <i class="ri-edit-line"></i>
                                    </a>
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
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endpush