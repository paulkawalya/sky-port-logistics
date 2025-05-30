@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Create a new tax type for invoices and quotations</p>
        </div>
        <a href="{{ route('taxes.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Taxes
        </a>
    </div>

    <!-- Create Tax Card -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-percent-line me-4 text-primary"></i>Tax Details
                    </h5>
                </div>
                <div class="card-body p-16">
                    <form action="{{ route('taxes.store') }}" method="post">
                        @csrf

                        <!-- Tax Name -->
                        <div class="mb-4">
                            <label for="tax_name" class="form-label fw-semibold">Tax Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-price-tag-3-line text-muted"></i>
                                </span>
                                <input type="text" name="tax_name" id="tax_name" class="form-control border-start-0 ps-0" placeholder="Enter tax name" required>
                            </div>
                            <div class="form-text">Enter a descriptive name for this tax (e.g. VAT, Income Tax)</div>
                        </div>

                        <div class="row">
                            <!-- Percentage Tax -->
                            <div class="col-md-6 mb-4">
                                <label for="percentage_tax" class="form-label fw-semibold">Percentage Rate</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-percent-line text-muted"></i>
                                    </span>
                                    <input type="number" name="percentage_tax" id="percentage_tax" min="0" step="0.01" class="form-control border-start-0 ps-0" placeholder="Enter percentage">
                                </div>
                                <div class="form-text">Percentage rate to apply (e.g. 18 for 18%)</div>
                            </div>

                            <!-- Fixed Amount -->
                            <div class="col-md-6 mb-4">
                                <label for="amount" class="form-label fw-semibold">Fixed Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-money-dollar-circle-line text-muted"></i>
                                    </span>
                                    <input type="number" name="amount" id="amount" min="0" step="0.01" class="form-control border-start-0 ps-0" placeholder="Enter fixed amount">
                                </div>
                                <div class="form-text">Fixed amount to apply (if applicable)</div>
                            </div>
                        </div>

                        <!-- Currency -->
                        <div class="mb-4">
                            <label for="currency" class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-exchange-dollar-line text-muted"></i>
                                </span>
                                <select name="currency" id="currency" class="form-select border-start-0 ps-0" required>
                                    <option value="UGX">UGX - Ugandan Shilling</option>
                                    <option value="USD">USD - US Dollar</option>
                                </select>
                            </div>
                            <div class="form-text">Select the currency for this tax</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('taxes.index') }}" class="btn btn-light me-4">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Save Tax
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