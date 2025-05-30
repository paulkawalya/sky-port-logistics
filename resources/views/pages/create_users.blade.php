@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Create a new user account with appropriate permissions</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Users
        </a>
    </div>

    <!-- Create User Card -->
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-add-line me-4 text-primary"></i>User Information
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

                    <form action="{{ route('users.store') }}" method="post">
                        @csrf

                        <!-- User Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-user-line text-muted"></i>
                                </span>
                                <input type="text" name="name" id="name" class="form-control border-start-0 ps-0" placeholder="Enter user's full name" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-text">Enter the user's complete name as it should appear in the system</div>
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-mail-line text-muted"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="Enter email address" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-text">This email will be used for login and notifications</div>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-phone-line text-muted"></i>
                                </span>
                                <input type="text" name="phone_number" id="phone_number" class="form-control border-start-0 ps-0" placeholder="Enter phone number" value="{{ old('phone_number') }}">
                            </div>
                            <div class="form-text">Contact number for the user (optional)</div>
                        </div>

                        <!-- User Type/Role -->
                        <div class="mb-4">
                            <label for="user_type" class="form-label fw-semibold">User Role <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-shield-user-line text-muted"></i>
                                </span>
                                <select name="user_type" id="user_type" class="form-select border-start-0 ps-0" required>
                                    <option value="">Select a role</option>
                                    @foreach($user_types as $user_type)
                                    <option value="{{$user_type}}" {{ old('user_type') == $user_type ? 'selected' : '' }}>
                                        {{$user_type}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text">The role determines the user's default permissions</div>
                        </div>

                        <!-- Password Information -->
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="ri-information-line me-4 fs-5"></i>
                            <div>
                                A default password <strong>user123@</strong> will be assigned to this user. They should change it after first login.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-danger me-4">
                                <i class="ri-close-line me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-1"></i> Create User
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