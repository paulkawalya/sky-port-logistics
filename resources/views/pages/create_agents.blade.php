@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Add a new agent to the system</p>
        </div>
        <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Agents
        </a>
    </div>

    <!-- Agent Form Card -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-user-add-line fs-4 me-4 text-primary"></i>
                        <h5 class="card-title mb-0">Agent Information</h5>
                    </div>
                </div>
                <div class="card-body p-16">
                    <form action="{{ route('agents.store') }}" method="post" class="needs-validation" novalidate>
                        @csrf

                        <!-- Agent Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Agent Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-user-3-line text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('name')) is-invalid @endif"
                                    id="name" name="name" value="{{ old('name') }}" required placeholder="Enter agent name">
                            </div>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                            @else
                                <div class="form-text">Enter the full name of the agent</div>
                            @endif
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-phone-line text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('phone_number')) is-invalid @endif"
                                    id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter phone number">
                            </div>
                            @if ($errors->has('phone_number'))
                                <div class="invalid-feedback d-block">{{ $errors->first('phone_number') }}</div>
                            @else
                                <div class="form-text">Enter a valid contact phone number</div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end mt-4 gap-4">
                            <button type="reset" class="btn btn-light px-8">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary px-8">
                                <i class="ri-save-line me-1"></i> Save Agent
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