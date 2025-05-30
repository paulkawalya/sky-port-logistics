@extends('layouts.app')

@section('content')
<div class="container-fluid py-8">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-8">
        <div>
            <h4 class="fw-bold mb-8">Admin Settings</h4>
            <p class="text-muted small mb-8">System administration and maintenance tools</p>
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

    <!-- Admin Tools Cards -->
    <div class="row g-4">
        <!-- Database Backup Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center mb-8">
                        <div style="width: 48px; height: 48px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <i class="ri-database-2-line" style="color: #0d6efd; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-8">Database Backup</h5>
                            <p class="card-text text-muted">Download a complete backup of the system database</p>
                        </div>
                    </div>
                    <p class="text-muted mb-8">
                        <i class="ri-information-line me-8"></i> This will create a SQL dump of the entire database that can be used for restoration if needed.
                    </p>
                    <div class="d-grid">
                        <a href="{{ route('backup.database') }}" class="btn btn-primary">
                            <i class="ri-download-cloud-line me-8"></i> Backup Database
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light p-8">
                    <small class="text-muted">
                        <i class="ri-time-line me-8"></i> Last backup: {{ date('Y-m-d H:i:s') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center mb-8">
                        <div style="width: 48px; height: 48px; border-radius: 8px; background-color: rgba(25, 135, 84, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <i class="ri-user-settings-line" style="color: #198754; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-8">User Management</h5>
                            <p class="card-text text-muted">Manage system users and their permissions</p>
                        </div>
                    </div>
                    <p class="text-muted mb-8">
                        <i class="ri-information-line me-8"></i> Add, edit, or remove users and configure their access permissions.
                    </p>
                    <div class="d-grid">
                        <a href="{{ route('users.index') }}" class="btn btn-success">
                            <i class="ri-user-add-line me-8"></i> Manage Users
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-light p-8">
                    <small class="text-muted">
                        <i class="ri-user-line me-8"></i> Total users: {{ \App\Models\User::count() }}
                    </small>
                </div>
            </div>
        </div>

        <!-- System Information Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-16">
                    <div class="d-flex align-items-center mb-8">
                        <div style="width: 48px; height: 48px; border-radius: 8px; background-color: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <i class="ri-information-line" style="color: #dc3545; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-8">System Information</h5>
                            <p class="card-text text-muted">View system details and configuration</p>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <h6 class="fw-bold">Environment</h6>
                        <p class="mb-8">{{ app()->environment() }}</p>
                        
                        <h6 class="fw-bold">PHP Version</h6>
                        <p class="mb-8">{{ phpversion() }}</p>
                        
                        <h6 class="fw-bold">Laravel Version</h6>
                        <p class="mb-8">{{ app()->version() }}</p>
                    </div>
                </div>
                <div class="card-footer bg-light p-8">
                    <small class="text-muted">
                        <i class="ri-calendar-line me-8"></i> Server time: {{ date('Y-m-d H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
