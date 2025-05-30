@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-checkbox-circle-line me-4"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-4"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Container details and related information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('containers.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back to Containers
            </a>
            @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['edit_containers']))
                <a href="{{ route('containers.edit', $transit->id) }}" class="btn btn-outline-primary">
                    <i class="ri-edit-line me-8"></i> Edit Container
                </a>
            @endif
            @if (Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['edit_containers']) && $transit->payment_status != 'paid')
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mark_as_paid_modal">
                    <i class="ri-checkbox-circle-line me-8"></i> Mark as Paid
                </button>
            @endif
            @if (Auth::user()->canDo(['view_shipment']))
                <a href="{{ route('containers.details', $transit->id) }}" class="btn btn-primary">
                    <i class="ri-dashboard-line me-8"></i> Comprehensive View
                </a>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4">
                @if(Auth::user()->canDo(['view_container_updates']))
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#update_status">
                        <i class="ri-refresh-line me-8"></i> Update Status
                    </button>
                @endif

                @if(Auth::user()->canDo(['add_shipment_documents']))
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#container_documents">
                        <i class="ri-file-add-line me-8"></i> Documents
                    </button>
                @endif

                @if(Auth::user()->canDo(['create_shipment']))
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#add_shippments">
                        <i class="ri-file-excel-line me-8"></i> Import Stuffing Report
                    </button>
                    <a href="{{ route('shippments.create') }}" class="btn btn-outline-success">
                        <i class="ri-add-circle-line me-8"></i> Add Shipments
                    </a>
                @endif

                @if(Auth::user()->canDo(['delete_container']))
                    <form action="{{route('containers.destroy',$transit->id)}}" method="post" class="d-inline" onsubmit="return confirm('Delete this container? This will permanently delete all the data related to this container.');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" type="submit">
                            <i class="ri-delete-bin-line me-8"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Shipments Section -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-truck-line me-4 text-primary"></i>Shipments
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tabledata">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Description</th>
                                    <th>Mark</th>
                                    <th>Shipper</th>
                                    <th>Invoice Bal</th>
                                    <th class="text-end actions-column">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shippments as $shippment)
                                <tr class="{{ $shippment->invoices->isNotEmpty() && $shippment->invoices->first()->balance($shippment->invoices->first()->id) == 0 ? 'table-success' : '' }}">
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('customers.show', $shippment->customer_id) }}" class="text-decoration-none fw-medium">
                                                {{ $shippment->customer->name }}
                                            </a>
                                            <span class="text-muted small">{{ $shippment->customer->phone_number }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $shippment->description }}</td>
                                    <td>{{ $shippment->mark }}</td>
                                    <td>{{ $shippment->shipper }}</td>
                                    <td>
                                        @if($shippment->invoices->isNotEmpty())
                                            @php
                                                $invoice = $shippment->invoices->first();
                                                $balance = $invoice->balance($invoice->id);
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                @if($balance == 0)
                                                    <span class="badge bg-success-subtle text-success me-4">Paid</span>
                                                @elseif($balance < $invoice->totalAmount($invoice->id))
                                                    <span class="badge bg-warning-subtle text-warning me-4">Partial</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger me-4">Unpaid</span>
                                                @endif
                                                {{ number_format($balance) }} {{ $invoice->transaction_currency }}
                                            </div>
                                        @else
                                            <span class="text-muted">No invoice found</span>
                                        @endif
                                    </td>
                                    <td class="text-end actions-column">
                                        <a href="{{ route('shippments.show', $shippment->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line me-8"></i> Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container Info Section -->
        <div class="col-md-4 mb-4">
            <!-- Status Section -->
            @if(Auth::user()->canDo(['view_container_updates']))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-information-line me-4 text-warning"></i>Status Updates
                    </h5>
                    @if(Auth::user()->canDo(['view_container_updates']))
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#update_status">
                            <i class="ri-refresh-line me-8"></i> Update
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($transit->statuses->count() > 0)
                    <div class="status-timeline">
                        @foreach($transit->statuses as $status)
                            <div class="status-item mb-4">
                                <div class="d-flex">
                                    <div class="status-icon me-3 position-relative">
                                        @php
                                            $statusColors = [
                                                'On Port' => ['bg' => '#17a2b8', 'icon' => 'ri-ship-line'],
                                                'Pickedup' => ['bg' => '#ffc107', 'icon' => 'ri-truck-line'],
                                                'In Transit' => ['bg' => '#0d6efd', 'icon' => 'ri-route-line'],
                                                'In Warehouse' => ['bg' => '#198754', 'icon' => 'ri-store-2-line'],
                                                'Transferred' => ['bg' => '#6c757d', 'icon' => 'ri-exchange-line'],
                                            ];
                                            $statusInfo = $statusColors[$status->status] ?? ['bg' => '#0063f7', 'icon' => 'ri-information-line'];
                                        @endphp
                                        <div class="status-icon-circle d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px; border-radius: 50%; background-color: {{ $statusInfo['bg'] }}; color: white; font-size: 16px;">
                                            <i class="{{ $statusInfo['icon'] }}"></i>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="status-line" style="position: absolute; top: 32px; left: 15px; width: 2px; height: calc(100% + 16px); background-color: #e9ecef;"></div>
                                        @endif
                                    </div>
                                    <div class="status-content bg-light p-8 rounded w-100" style="border-left: 3px solid {{ $statusInfo['bg'] }};">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h6 class="mb-0 fw-bold">{{ $status->status }}</h6>
                                            <span class="badge bg-light text-dark px-2 py-1 rounded-pill">
                                                {{ $status->created_at->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                        <p class="mb-4">{{ $status->narrative }}</p>
                                        <p class="text-muted small mb-0">
                                            <i class="ri-user-line me-8"></i> Updated by {{ $status->user->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="mb-8 ">
                            <i class="ri-information-line" style="font-size: 48px; color: #6c757d;"></i>
                        </div>
                        <h6 class="text-muted">No status updates available</h6>
                        <p class="text-muted small">Click the Update button to add a new status update.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Container Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="ri-ship-line me-4 text-primary"></i>Container Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-8 ">
                        <div style="width: 48px; height: 48px; border-radius: 8px; background-color: rgba(0, 99, 247, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                            <i class="ri-ship-line" style="color: #0063f7; font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $transit->number }}</h5>
                            <p class="text-muted small mb-0">Created: {{ $transit->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="row g-8 mb-8 ">
                        <!-- Category & Size -->
                        <div class="col-6">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Category</p>
                                <p class="mb-0 fw-medium">{{ $transit->category }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Size</p>
                                <p class="mb-0 fw-medium">{{ $transit->size ?: 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="col-12">
                            <div class="p-8 rounded" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.05), rgba(13, 110, 253, 0.1));">
                                <p class="text-muted mb-1 small">Payment Status</p>
                                <div class="d-flex align-items-center">
                                    @if($transit->payment_status == 'paid')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 me-4">Paid</span>
                                    @elseif($transit->payment_status == 'partialy paid')
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 me-4">Partially Paid</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 me-4">Unpaid</span>
                                    @endif
                                    <p class="mb-0 fw-medium">{{ $transit->payment_status ?: 'Not Paid' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Route Information -->
                    <h6 class="fw-bold mb-8  text-primary">Route Information</h6>
                    <div class="route-info position-relative mb-4 ps-8 ">
                        <div class="route-line position-absolute" style="top: 0; bottom: 0; left: 10px; width: 2px; background-color: #e9ecef;"></div>

                        <div class="route-point mb-8  position-relative">
                            <div class="position-absolute" style="width: 12px; height: 12px; border-radius: 50%; background-color: #0d6efd; left: -6px; top: 4px;"></div>
                            <div class="ps-8 ">
                                <p class="text-muted mb-1 small">Departure</p>
                                <p class="mb-0 fw-medium">{{ $transit->departure_place ?: 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="route-point mb-8  position-relative">
                            <div class="position-absolute" style="width: 12px; height: 12px; border-radius: 50%; background-color: #ffc107; left: -6px; top: 4px;"></div>
                            <div class="ps-8 ">
                                <p class="text-muted mb-1 small">Expected Arrival</p>
                                <p class="mb-0 fw-medium">{{ $transit->expected_arrival_time ?: 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="route-point position-relative">
                            <div class="position-absolute" style="width: 12px; height: 12px; border-radius: 50%; background-color: #198754; left: -6px; top: 4px;"></div>
                            <div class="ps-8 ">
                                <p class="text-muted mb-1 small">Destination</p>
                                <p class="mb-0 fw-medium">{{ $transit->destination_place ?: 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <h6 class="fw-bold mb-8  text-primary">Additional Information</h6>
                    <div class="row g-8">
                        <div class="col-6">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Transporter</p>
                                <p class="mb-0 fw-medium">{{ $transit->contact_information ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Clearing Agent</p>
                                <p class="mb-0 fw-medium">{{ $transit->clearing_agent ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-8 rounded bg-light">
                                <p class="text-muted mb-1 small">Created By</p>
                                <p class="mb-0 fw-medium">{{ $transit->user->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            @if(Auth::user()->canDo(['view_container_documents']))
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-file-line me-4 text-info"></i>Container Documents
                    </h5>
                    @if(Auth::user()->canDo(['add_shipment_documents']))
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#container_documents">
                            <i class="ri-file-add-line me-8"></i> Add Document
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($transit->documents->count() > 0)
                    <div class="document-list">
                        @foreach($transit->documents as $document)
                            @php
                                $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                $iconClass = 'ri-file-text-line';
                                $colorClass = 'text-primary';

                                if (in_array(strtolower($extension), ['pdf'])) {
                                    $iconClass = 'ri-file-pdf-line';
                                    $colorClass = 'text-danger';
                                } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                    $iconClass = 'ri-file-word-line';
                                    $colorClass = 'text-primary';
                                } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                    $iconClass = 'ri-file-excel-line';
                                    $colorClass = 'text-success';
                                } elseif (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $iconClass = 'ri-image-line';
                                    $colorClass = 'text-info';
                                }
                            @endphp
                            <a href="{{ asset('documents/'.$document->file_url) }}" class="document-item d-flex align-items-center p-8 mb-4 rounded bg-light text-decoration-none" target="_blank">
                                <div class="document-icon me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1);">
                                    <i class="{{ $iconClass }} {{ $colorClass }}" style="font-size: 20px;"></i>
                                </div>
                                <div class="document-info flex-grow-1">
                                    <h6 class="mb-1 text-dark">{{ $document->file_name }}</h6>
                                    <p class="mb-0 text-muted small">Added {{ $document->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="document-action">
                                    <i class="ri-download-line text-primary"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="mb-8 ">
                            <i class="ri-file-list-3-line" style="font-size: 48px; color: #6c757d;"></i>
                        </div>
                        <h6 class="text-muted">No documents available</h6>
                        <p class="text-muted small">Click the Add Document button to upload container documents.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@include('models.container_status')

@include('models.container_document')

@include('models.add_shippment')

<!-- Mark as Paid Modal -->
<div class="modal fade" id="mark_as_paid_modal" tabindex="-1" aria-labelledby="markAsPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markAsPaidModalLabel">Mark Container as Paid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="ri-information-line me-4"></i>
                    <span>Are you sure you want to mark this container as paid?</span>
                </div>
                <p>This will update the payment status from <strong>{{ $transit->payment_status ?: 'not paid' }}</strong> to <strong>paid</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-circle-line me-8"></i> Cancel
                </button>
                <form action="{{ route('containers.mark-as-paid', $transit->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="ri-checkbox-circle-line me-8"></i> Mark as Paid
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@include('layouts.data_tables')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add CSS for enhanced UI elements
        const style = document.createElement('style');
        style.textContent = `
            /* Status Timeline Styles */
            .status-timeline {
                position: relative;
                max-height: 400px;
                overflow-y: auto;
                padding-right: 10px;
            }
            .status-item {
                position: relative;
            }
            .status-content {
                transition: all 0.2s ease;
            }
            .status-content:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            }

            /* Document List Styles */
            .document-item {
                transition: all 0.2s ease;
                border: 1px solid transparent;
            }
            .document-item:hover {
                transform: translateY(-2px);
                border-color: #dee2e6;
                box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            }

            /* Route Info Styles */
            .route-info {
                position: relative;
            }
            .route-point {
                transition: all 0.2s ease;
            }
            .route-point:hover {
                transform: translateX(3px);
            }
        `;
        document.head.appendChild(style);

        // Customize the existing DataTable
        if ($.fn.dataTable.isDataTable('#tabledata')) {
            const table = $('#tabledata').DataTable();

            // Update search placeholder
            $('.dataTables_filter input')
                .attr('placeholder', 'Search shipments...')
                .parent().find('label').html(function() {
                    return $(this).html().replace('Search:', "<i class='ri-search-line'></i>");
                });

            // Update length menu text
            $('.dataTables_length label').html(function() {
                return $(this).html()
                    .replace('Show', "<span class='me-4'>Show</span>")
                    .replace('entries', "<span class='ms-2'>entries</span>");
            });
        }
    });
</script>
@endpush