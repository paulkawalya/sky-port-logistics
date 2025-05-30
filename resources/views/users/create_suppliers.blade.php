@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Add a new supplier or vendor to your business contacts</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Suppliers
        </a>
    </div>

    <!-- Create Supplier Card -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-add-line me-4 text-primary"></i>Supplier Details
                    </h5>
                </div>
                <div class="card-body p-16">
                    <form action="{{ route('suppliers.store') }}" method="post">
                        @csrf

                        <!-- Supplier Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-building-line text-muted"></i>
                                </span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 ps-0" placeholder="Enter supplier name" required>
                            </div>
                            <div class="form-text">Enter the full name of the company or individual supplier</div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-phone-line text-muted"></i>
                                </span>
                                <input type="text" name="phone_number" id="phone_number" class="form-control border-start-0 ps-0" placeholder="Enter contact phone number">
                            </div>
                            <div class="form-text">Primary contact number for this supplier</div>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-map-pin-line text-muted"></i>
                                </span>
                                <textarea name="address" id="address" class="form-control border-start-0 ps-0" rows="3" placeholder="Enter supplier address"></textarea>
                            </div>
                            <div class="form-text">Physical location or mailing address</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-light me-4">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Save Supplier
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

    textarea.form-control {
        min-height: 100px;
    }
</style>
@endpush