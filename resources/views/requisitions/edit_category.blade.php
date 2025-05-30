@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Update expense category information</p>
        </div>
        <a href="{{ route('expense_categories.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-8"></i> Back to Categories
        </a>
    </div>

    <!-- Edit Category Card -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-edit-line me-4 text-primary"></i>Edit Category
                    </h5>
                </div>
                <div class="card-body p-16">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('expense_categories.update', $expenseCategory->id) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-price-tag-3-line text-muted"></i>
                                </span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 ps-0" placeholder="Enter category name" value="{{ old('name', $expenseCategory->name) }}" required>
                            </div>
                            <div class="form-text">Enter a descriptive name for this expense category</div>
                        </div>

                        <!-- Particular -->
                        <div class="mb-4">
                            <label for="particular" class="form-label fw-semibold">Particular <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-file-list-3-line text-muted"></i>
                                </span>
                                <input type="text" name="particular" id="particular" class="form-control border-start-0 ps-0" placeholder="Enter particular details" value="{{ old('particular', $expenseCategory->particular) }}" required>
                            </div>
                            <div class="form-text">Provide additional details about this expense category</div>
                        </div>

                        <!-- Category Status -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success text-white me-4">
                                    <i class="ri-checkbox-circle-line me-8"></i> Active
                                </span>
                                <span class="text-muted small">This category is currently active</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('expense_categories.index') }}" class="btn btn-danger me-4">
                                <i class="ri-close-line me-8"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-8"></i> Update Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-label {
        font-size: 14px;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 12px;
        color: #6c757d;
    }

    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
</style>
@endpush