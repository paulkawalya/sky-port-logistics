@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Update customer information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i> Back to Customers
            </a>
            @if(Auth::user()->canDo(['view_customers_shipments']))
            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-primary">
                <i class="ri-ship-line me-1"></i> View Shipments
            </a>
            @endif
        </div>
    </div>

    <!-- Customer Form Card -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-edit-line fs-4 me-4 text-primary"></i>
                        <h5 class="card-title mb-0">Edit Customer Information</h5>
                    </div>
                </div>
                <div class="card-body p-16">
                    <form action="{{ route('customers.update', $customer->id) }}" method="post" class="needs-validation" novalidate>
                        @csrf
                        @method('PATCH')

                        <!-- Customer Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-user-3-line text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('name')) is-invalid @endif"
                                    id="name" name="name" value="{{ old('name', $customer->name) }}" required placeholder="Enter customer name">
                            </div>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-phone-line text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('phone_number')) is-invalid @endif"
                                    id="phone_number" name="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" required placeholder="Enter phone number">
                            </div>
                            @if ($errors->has('phone_number'))
                                <div class="invalid-feedback d-block">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end mt-4 gap-4">
                            <a href="{{ route('customers.index') }}" class="btn btn-light px-8">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-8">
                                <i class="ri-save-line me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.needs-validation');

        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
</script>
@endpush