@extends('layouts.app')

@section('content')
<div class="container-fluid py-8">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-8">
        <div>
            <h4 class="fw-bold mb-8">{{ $title }}</h4>
            <p class="text-muted small mb-8">View and manage user information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back to Users
            </a>
            @if(in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-primary">
                <i class="ri-edit-line me-8"></i> Edit User
            </a>
            <a href="{{ route('permissions.show', $user->id) }}" class="btn btn-outline-success">
                <i class="ri-lock-line me-8"></i> Manage Permissions
            </a>
            @endif
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-checkbox-circle-line me-8"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-8"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- User Profile Card -->
        <div class="col-lg-4 mb-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-16">
                    <!-- User Avatar -->
                    @php
                        $nameParts = explode(' ', $user->name);
                        $initials = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');

                        // Generate a consistent color based on user ID
                        $colors = ['#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0', '#4895ef', '#560bad', '#f15bb5', '#fee440', '#00bbf9'];
                        $colorIndex = $user->id % count($colors);
                        $avatarColor = $colors[$colorIndex];
                    @endphp
                    <div class="avatar rounded-circle mx-auto mb-8" style="width: 120px; height: 120px; background-color: {{ $avatarColor }}; color: white; font-size: 48px; display: flex; align-items: center; justify-content: center;">
                        {{ $initials }}
                    </div>
                    <h4 class="fw-bold mb-8">{{ $user->name }}</h4>
                    <p class="text-muted mb-8">{{ $user->email }}</p>
                    <span class="badge {{
                        $user->user_type == 'Admin' ? 'bg-danger' :
                        ($user->user_type == 'MD' ? 'bg-dark' :
                        (in_array($user->user_type, ['Manager', 'Accountant']) ? 'bg-success' :
                        (in_array($user->user_type, ['Operations', 'Store']) ? 'bg-primary' :
                        'bg-info'))) }} text-white px-3 py-2 mb-8">
                        {{ $user->user_type }}
                    </span>

                    @if($user->phone_number)
                    <div class="d-flex align-items-center justify-content-center mt-8">
                        <i class="ri-phone-line text-muted me-8"></i>
                        <span>{{ $user->phone_number }}</span>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-light p-8">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="ri-time-line me-8"></i> Joined: {{ $user->created_at->format('M d, Y') }}
                        </small>
                        <small class="text-muted">
                            <i class="ri-user-follow-line me-8"></i> ID: {{ $user->id }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Details and Actions -->
        <div class="col-lg-8 mb-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-8">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-settings-line me-8 text-primary"></i>User Information
                    </h5>
                </div>
                <div class="card-body p-16">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-8">
                                <h6 class="fw-bold text-muted mb-8">Full Name</h6>
                                <p class="mb-0 fs-5">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-8">
                                <h6 class="fw-bold text-muted mb-8">Email Address</h6>
                                <p class="mb-0 fs-5">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-8">
                                <h6 class="fw-bold text-muted mb-8">Phone Number</h6>
                                <p class="mb-0 fs-5">{{ $user->phone_number ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-8">
                                <h6 class="fw-bold text-muted mb-8">User Type</h6>
                                <p class="mb-0 fs-5">{{ $user->user_type }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-8">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-8">
                                <h6 class="fw-bold text-muted mb-8">Account Created</h6>
                                <p class="mb-0">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-8">
                                <h6 class="fw-bold text-muted mb-8">Last Updated</h6>
                                <p class="mb-0">{{ $user->updated_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    @if(in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))
                    <div class="mt-8">
                        <h5 class="fw-bold mb-8">Admin Actions</h5>
                        <div class="d-flex flex-wrap gap-4">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-8"></i> Edit User
                            </a>
                            <a href="{{ route('permissions.show', $user->id) }}" class="btn btn-success">
                                <i class="ri-lock-line me-8"></i> Manage Permissions
                            </a>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                <i class="ri-key-line me-8"></i> Reset Password
                            </button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                    <i class="ri-delete-bin-line me-8"></i> Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password for {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-8"></i> This will reset the user's password to a new system-generated password. The user will need to change it upon next login.
                    </div>
                    <div class="mb-8">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="text" class="form-control" id="new_password" name="new_password" value="{{ Str::random(10) }}" readonly>
                        <div class="form-text">This password will be set for the user. Make sure to share it with them securely.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
