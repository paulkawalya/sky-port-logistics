@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid py-4">
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

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Manage system users and their access permissions</p>
        </div>
        @if(Auth::user()->canDo(['create_users']))
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="ri-user-add-line me-1"></i> Add New User
        </a>
        @endif
    </div>

    <!-- User Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Users Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-user-line text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ count($users) }}</h5>
                            <p class="text-muted small mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Users Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="ri-admin-line text-danger"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $users->where('user_type', 'Admin')->count() }}</h5>
                            <p class="text-muted small mb-0">Admin Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Users Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-team-line text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $users->whereIn('user_type', ['Manager', 'Accountant', 'Operations'])->count() }}</h5>
                            <p class="text-muted small mb-0">Staff Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Field Users Card -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px; background-color: rgba(13, 202, 240, 0.1);">
                            <i class="ri-user-location-line text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $users->whereIn('user_type', ['Agent', 'Field', 'freight'])->count() }}</h5>
                            <p class="text-muted small mb-0">Field Users</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ri-user-settings-line me-4 text-primary"></i>User Management
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
                            <li><a class="dropdown-item" href="#" id="export-csv"><i class="ri-file-text-line me-4 text-info"></i>CSV</a></li>
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
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $nameParts = explode(' ', $user->name);
                                        $initials = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');

                                        // Generate a consistent color based on user ID
                                        $colors = ['#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0', '#4895ef', '#560bad', '#f15bb5', '#fee440', '#00bbf9'];
                                        $colorIndex = $user->id % count($colors);
                                        $avatarColor = $colors[$colorIndex];
                                    @endphp
                                    <div class="avatar rounded-circle me-3" style="width: 40px; height: 40px; background-color: {{ $avatarColor }}; color: white; font-size: 16px; display: flex; align-items: center; justify-content: center;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <span class="fw-semibold">{{ $user->name }}</span>
                                        @if($user->user_type == 'MD')
                                        <span class="badge bg-danger ms-1">MD</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ri-mail-line text-muted me-4"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </td>
                            <td>
                                @if($user->phone_number)
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line text-muted me-4"></i>
                                    <span>{{ $user->phone_number }}</span>
                                </div>
                                @else
                                <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{
                                    $user->user_type == 'Admin' ? 'bg-danger' :
                                    ($user->user_type == 'MD' ? 'bg-dark' :
                                    (in_array($user->user_type, ['Manager', 'Accountant']) ? 'bg-success' :
                                    (in_array($user->user_type, ['Operations', 'Store']) ? 'bg-primary' :
                                    'bg-info'))) }} text-white">
                                    {{ $user->user_type }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success text-white">
                                    <i class="ri-checkbox-circle-line me-1"></i> Active
                                </span>
                            </td>
                            <td>
                                @if(in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))
                                <div class="d-flex gap-1">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit User">
                                        <i class="ri-edit-line"></i>
                                    </a>
                                    <a href="{{ route('permissions.show', $user->id) }}" class="btn btn-sm btn-outline-success" title="Manage Permissions">
                                        <i class="ri-lock-line"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-warning" title="Reset Password" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $user->id }}">
                                        <i class="ri-key-line"></i>
                                    </button>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
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

<!-- Reset Password Modals -->
@foreach($users as $user)
<div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1" aria-labelledby="resetPasswordModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel{{ $user->id }}">Reset Password for {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-8"></i> This will reset the user's password to a new system-generated password. The user will need to change it upon next login.
                    </div>
                    <div class="mb-8">
                        <label for="new_password{{ $user->id }}" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="new_password{{ $user->id }}" name="new_password" value="{{ Str::random(10) }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyPassword({{ $user->id }})" title="Copy to clipboard">
                                <i class="ri-clipboard-line"></i>
                            </button>
                        </div>
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
@endforeach

@include('layouts.data_tables')

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tabledata').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="ri-file-excel-2-line"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ri-file-pdf-line"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="ri-file-text-line"></i> CSV',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="ri-printer-line"></i> Print',
                    className: 'btn btn-dark btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],
            responsive: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search users...",
                lengthMenu: "Show _MENU_ users per page",
                zeroRecords: "No users found",
                info: "Showing _START_ to _END_ of _TOTAL_ users",
                infoEmpty: "No users available",
                infoFiltered: "(filtered from _MAX_ total users)"
            }
        });
    });
</script>
<script>
    // Function to copy password to clipboard
    function copyPassword(userId) {
        const passwordField = document.getElementById('new_password' + userId);
        passwordField.select();
        document.execCommand('copy');

        // Show a tooltip or some indication that the password was copied
        const button = event.currentTarget;
        const originalTitle = button.getAttribute('title');
        button.setAttribute('title', 'Copied!');
        button.innerHTML = '<i class="ri-check-line"></i>';

        // Reset the button after a short delay
        setTimeout(() => {
            button.setAttribute('title', originalTitle);
            button.innerHTML = '<i class="ri-clipboard-line"></i>';
        }, 2000);
    }

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
        // Export functionality
        document.getElementById('export-excel').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-excel:first').click();
        });

        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-pdf:first').click();
        });

        document.getElementById('export-csv').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-csv:first').click();
        });

        document.getElementById('export-print').addEventListener('click', function(e) {
            e.preventDefault();
            $('.buttons-print:first').click();
        });
    });
</script>
@endpush
