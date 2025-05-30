@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">My Profile</h4>
            <p class="text-muted small mb-0">Manage your account information and security settings</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="ri-dashboard-line me-1"></i> Dashboard
        </a>
    </div>

    <div class="row g-4">
        <!-- Left Column: Profile Info -->
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-16">
                    <!-- User Avatar -->
                    @php
                        $name = Auth::user()->name;
                        $nameParts = explode(' ', $name);
                        $initials = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');

                        // Generate a consistent color based on user ID
                        $colors = ['#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0', '#4895ef', '#560bad', '#f15bb5', '#fee440', '#00bbf9'];
                        $colorIndex = Auth::id() % count($colors);
                        $avatarColor = $colors[$colorIndex];
                    @endphp
                    <div class="avatar rounded-circle mx-auto mb-4" style="width: 120px; height: 120px; background-color: {{ $avatarColor }}; color: white; font-size: 48px; display: flex; align-items: center; justify-content: center;">
                        {{ $initials }}
                    </div>
                    <h4 class="fw-bold mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-4">{{ Auth::user()->email }}</p>
                    <span class="badge {{
                        Auth::user()->user_type == 'Admin' ? 'bg-danger' :
                        (Auth::user()->user_type == 'MD' ? 'bg-dark' :
                        (in_array(Auth::user()->user_type, ['Manager', 'Accountant']) ? 'bg-success' :
                        (in_array(Auth::user()->user_type, ['Operations', 'Store']) ? 'bg-primary' :
                        'bg-info'))) }} text-white px-3 py-2 mb-3">
                        {{ Auth::user()->user_type }}
                    </span>

                    @if(Auth::user()->phone_number)
                    <div class="d-flex align-items-center justify-content-center mt-3">
                        <i class="ri-phone-line text-muted me-4"></i>
                        <span>{{ Auth::user()->phone_number }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Update Name Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-settings-line me-4 text-primary"></i>Update Profile
                    </h5>
                </div>
                <div class="card-body p-16">
                    <form method="POST" action="{{ route('profile.update-name') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-user-line text-muted"></i>
                                </span>
                                <input type="text" name="name" class="form-control border-start-0 ps-0" id="name" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="form-text">Your name as it appears throughout the system</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-save-line me-1"></i> Update Profile
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Security Settings -->
        <div class="col-lg-8">
            <!-- Password Change Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-lock-password-line me-4 text-primary"></i>Security Settings
                    </h5>
                </div>
                <div class="card-body p-16">
                    @if(session('status'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="ri-checkbox-circle-line me-4 fs-5"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-lock-line text-muted"></i>
                                </span>
                                <input type="password" name="current_password" class="form-control border-start-0 ps-0" id="current_password" required>
                            </div>
                            <div class="form-text">Enter your current password to verify your identity</div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="new_password" class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-lock-password-line text-muted"></i>
                                </span>
                                <input type="password" name="new_password" class="form-control border-start-0 ps-0" id="new_password" required>
                            </div>
                            <div class="form-text">Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters</div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-checkbox-circle-line text-muted"></i>
                                </span>
                                <input type="password" name="new_password_confirmation" class="form-control border-start-0 ps-0" id="new_password_confirmation" required>
                            </div>
                            <div class="form-text">Re-enter your new password to confirm</div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="alert alert-info d-flex" role="alert">
                            <i class="ri-information-line me-4 fs-5 align-self-start"></i>
                            <div>
                                <p class="mb-4">Your password must meet the following requirements:</p>
                                <ul class="mb-0">
                                    <li>At least 8 characters long</li>
                                    <li>Include at least one uppercase letter (A-Z)</li>
                                    <li>Include at least one lowercase letter (a-z)</li>
                                    <li>Include at least one number (0-9)</li>
                                    <li>Include at least one special character (@$!%*?&)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-lock-password-line me-1"></i> Update Password
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

    .avatar {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
</style>
@endpush
