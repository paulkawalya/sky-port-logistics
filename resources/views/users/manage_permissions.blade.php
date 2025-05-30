@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{$title}}</h4>
            <p class="text-muted small mb-0">Configure access permissions for {{ $user->name }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to Users
        </a>
    </div>

    <!-- User Info Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-16">
            <div class="d-flex align-items-center">
                @php
                    $nameParts = explode(' ', $user->name);
                    $initials = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');

                    // Generate a consistent color based on user ID
                    $colors = ['#4361ee', '#3a0ca3', '#7209b7', '#f72585', '#4cc9f0', '#4895ef', '#560bad', '#f15bb5', '#fee440', '#00bbf9'];
                    $colorIndex = $user->id % count($colors);
                    $avatarColor = $colors[$colorIndex];
                @endphp
                <div class="avatar rounded-circle me-3" style="width: 50px; height: 50px; background-color: {{ $avatarColor }}; color: white; font-size: 20px; display: flex; align-items: center; justify-content: center;">
                    {{ $initials }}
                </div>
                <div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <div class="d-flex align-items-center">
                        <span class="badge {{
                            $user->user_type == 'Admin' ? 'bg-danger' :
                            ($user->user_type == 'MD' ? 'bg-dark' :
                            (in_array($user->user_type, ['Manager', 'Accountant']) ? 'bg-success' :
                            (in_array($user->user_type, ['Operations', 'Store']) ? 'bg-primary' :
                            'bg-info'))) }} text-white me-4">
                            {{ $user->user_type }}
                        </span>
                        <span class="text-muted small">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Message -->
    <div class="alert alert-info mb-4" role="alert" id="display_message" style="display: none;">
        <div class="d-flex align-items-center">
            <i class="ri-information-line me-4 fs-5"></i>
            <span>Processing...</span>
        </div>
    </div>

    <!-- Permissions Cards -->
    <div class="row g-3">
        @foreach($readModelComponents as $readModelComponent)
        <div class="col-lg-6 col-md-12 mb-16">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        @if($readModelComponent->name == 'User')
                            <i class="ri-user-settings-line me-4 text-primary"></i>
                        @elseif($readModelComponent->name == 'Customers')
                            <i class="ri-user-star-line me-4 text-success"></i>
                        @elseif($readModelComponent->name == 'Agents')
                            <i class="ri-team-line me-4 text-info"></i>
                        @elseif($readModelComponent->name == 'Containers')
                            <i class="ri-inbox-archive-line me-4 text-warning"></i>
                        @elseif($readModelComponent->name == 'Shipments')
                            <i class="ri-ship-line me-4 text-danger"></i>
                        @elseif($readModelComponent->name == 'Invoices')
                            <i class="ri-bill-line me-4 text-primary"></i>
                        @elseif($readModelComponent->name == 'Quotations')
                            <i class="ri-file-list-3-line me-4 text-success"></i>
                        @elseif($readModelComponent->name == 'Taxes')
                            <i class="ri-percent-line me-4 text-info"></i>
                        @elseif($readModelComponent->name == 'Suppliers')
                            <i class="ri-store-2-line me-4 text-warning"></i>
                        @elseif($readModelComponent->name == 'Transactions')
                            <i class="ri-exchange-dollar-line me-4 text-danger"></i>
                        @else
                            <i class="ri-settings-line me-4 text-primary"></i>
                        @endif
                        {{ $readModelComponent->name }} Permissions
                    </h5>
                </div>
                <div class="card-body p-16">
                    <div class="row g-3">
                        @foreach($readModelComponent->permissions as $permission)
                        <div class="col-md-6">
                            <div class="form-check permission-check">
                                @if(App\Models\UserPermission::checkUserPermision($user->id, $permission->id))
                                    <input class="form-check-input" type="checkbox" checked value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                @else
                                    <input class="form-check-input" type="checkbox" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                @endif
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                    {{ $permission->display_name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end mt-4">
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary me-4">
            <i class="ri-user-settings-line me-1"></i> Edit User
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-danger">
            <i class="ri-arrow-left-line me-1"></i> Back to Users
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .permission-check {
        padding: 10px 15px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .permission-check:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize display message
        const displayMessage = $("#display_message");

        // Handle all permission checkboxes
        $('input[id^="permission_"]').on("change", function() {
            const checkbox = $(this);
            const permissionId = checkbox.val();

            // Show processing message
            displayMessage.text("Processing...").show();

            // Send AJAX request
            $.ajax({
                type: "PATCH",
                url: "{{route('permissions.update', $user->id)}}",
                data: {
                    permission_id: permissionId,
                    _token: "{{Session::token()}}",
                },
                success: function(result) {
                    // Show success message with animation
                    displayMessage.html('<div class="d-flex align-items-center"><i class="ri-checkbox-circle-line me-4 text-success fs-5"></i>' + result + '</div>');
                    displayMessage.removeClass('alert-info').addClass('alert-success');

                    // Hide message after 3 seconds
                    setTimeout(function() {
                        displayMessage.fadeOut('slow', function() {
                            displayMessage.removeClass('alert-success').addClass('alert-info');
                        });
                    }, 3000);
                },
                error: function() {
                    // Show error message
                    displayMessage.html('<div class="d-flex align-items-center"><i class="ri-error-warning-line me-4 text-danger fs-5"></i>Error updating permission</div>');
                    displayMessage.removeClass('alert-info').addClass('alert-danger');

                    // Revert checkbox state
                    checkbox.prop('checked', !checkbox.prop('checked'));

                    // Hide message after 3 seconds
                    setTimeout(function() {
                        displayMessage.fadeOut('slow', function() {
                            displayMessage.removeClass('alert-danger').addClass('alert-info');
                        });
                    }, 3000);
                }
            });
        });
    });
</script>
@endpush