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
            <p class="text-muted small mb-0">Create a new shipment in the system</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('shippments.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back to Shipments
            </a>
        </div>
    </div>

    <!-- Shipment Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-add-line fs-4 me-4 text-primary"></i>
                        <h5 class="card-title mb-0">New Shipment Information</h5>
                    </div>
                </div>
                <div class="card-body p-16">
                    <form action="{{ url('save_shipment') }}" method="post" class="needs-validation" novalidate>
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <!-- Container Selection -->
                                <div class="mb-4">
                                    <label for="transit_id" class="form-label fw-semibold">
                                        <i class="ri-ship-2-line text-primary me-4"></i>
                                        Container <span class="text-danger">*</span>
                                    </label>
                                    <select name="transit_id" id="transit_id" class="form-control @if($errors->has('transit_id')) is-invalid @endif" required>
                                        <option value="">Select Container</option>
                                        @foreach($tarnsits as $tarnsit)
                                        <option value="{{ $tarnsit->id }}">{{ $tarnsit->number }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('transit_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('transit_id') }}</div>
                                    @endif
                                </div>

                                <!-- Customer Selection -->
                                <div class="mb-4">
                                    <label for="customer_id" class="form-label fw-semibold">
                                        <i class="ri-user-3-line text-primary me-4"></i>
                                        Customer <span class="text-danger">*</span>
                                    </label>
                                    <select name="customer_id" id="customer_id" class="form-control @if($errors->has('customer_id')) is-invalid @endif" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name." - ".$customer->phone_number }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('customer_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('customer_id') }}</div>
                                    @endif
                                </div>

                                <!-- Agent Selection -->
                                <div class="mb-4">
                                    <label for="agent_id" class="form-label fw-semibold">
                                        <i class="ri-user-star-line text-primary me-4"></i>
                                        Agent <span class="text-danger">*</span>
                                    </label>
                                    <select name="agent_id" id="agent_id" class="form-control @if($errors->has('agent_id')) is-invalid @endif" required>
                                        <option value="">Select Agent</option>
                                        @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}">{{ $agent->name." - ".$agent->phone_number }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('agent_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('agent_id') }}</div>
                                    @endif
                                </div>

                                <!-- Shipper -->
                                <div class="mb-4">
                                    <label for="shipper" class="form-label fw-semibold">Shipper</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-truck-line text-muted"></i>
                                        </span>
                                        <input type="text" name="shipper" id="shipper" value="{{ old('shipper') }}" class="form-control border-start-0 ps-0 @if($errors->has('shipper')) is-invalid @endif" placeholder="Enter shipper name">
                                    </div>
                                    @if ($errors->has('shipper'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('shipper') }}</div>
                                    @endif
                                </div>

                                <!-- Mark -->
                                <div class="mb-4">
                                    <label for="mark" class="form-label fw-semibold">Mark</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-price-tag-3-line text-muted"></i>
                                        </span>
                                        <input type="text" name="mark" id="mark" value="{{ old('mark') }}" class="form-control border-start-0 ps-0 @if($errors->has('mark')) is-invalid @endif" placeholder="Enter mark">
                                    </div>
                                    @if ($errors->has('mark'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('mark') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- BL Packages -->
                                <div class="mb-4">
                                    <label for="bl_packages" class="form-label fw-semibold">No. of Packages on BL</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-stack-line text-muted"></i>
                                        </span>
                                        <input type="text" name="bl_packages" id="bl_packages" value="{{ old('bl_packages') }}" class="form-control border-start-0 ps-0 @if($errors->has('bl_packages')) is-invalid @endif" placeholder="Enter BL packages">
                                    </div>
                                    @if ($errors->has('bl_packages'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('bl_packages') }}</div>
                                    @endif
                                </div>

                                <!-- Number of Packages -->
                                <div class="mb-4">
                                    <label for="number_of_packages" class="form-label fw-semibold">No. of Packages</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-archive-line text-muted"></i>
                                        </span>
                                        <input type="number" name="number_of_packages" id="number_of_packages" value="{{ old('number_of_packages') }}" class="form-control border-start-0 ps-0 @if($errors->has('number_of_packages')) is-invalid @endif" placeholder="Enter number of packages">
                                    </div>
                                    @if ($errors->has('number_of_packages'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('number_of_packages') }}</div>
                                    @endif
                                </div>

                                <!-- Unit of Measure -->
                                <div class="mb-4">
                                    <label for="unit_of_measure" class="form-label fw-semibold">Type of Packages</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-scales-3-line text-muted"></i>
                                        </span>
                                        <input type="text" name="unit_of_measure" id="unit_of_measure" value="{{ old('unit_of_measure') }}" class="form-control border-start-0 ps-0 @if($errors->has('unit_of_measure')) is-invalid @endif" placeholder="Enter unit of measure">
                                    </div>
                                    @if ($errors->has('unit_of_measure'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('unit_of_measure') }}</div>
                                    @endif
                                </div>

                                <!-- Weight -->
                                <div class="mb-4">
                                    <label for="weight" class="form-label fw-semibold">Weight</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-scales-line text-muted"></i>
                                        </span>
                                        <input type="number" name="weight" id="weight" value="{{ old('weight') }}" class="form-control border-start-0 ps-0 @if($errors->has('weight')) is-invalid @endif" placeholder="Enter weight">
                                    </div>
                                    @if ($errors->has('weight'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('weight') }}</div>
                                    @endif
                                </div>

                                <!-- Volume -->
                                <div class="mb-4">
                                    <label for="volume" class="form-label fw-semibold">Volume</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-shape-2-line text-muted"></i>
                                        </span>
                                        <input type="number" name="volume" id="volume" value="{{ old('volume') }}" class="form-control border-start-0 ps-0 @if($errors->has('volume')) is-invalid @endif" placeholder="Enter volume">
                                    </div>
                                    @if ($errors->has('volume'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('volume') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Type -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="type" class="form-label fw-semibold">Type</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-list-check-2 text-muted"></i>
                                        </span>
                                        <input type="text" name="type" id="type" value="{{ old('type') }}" class="form-control border-start-0 ps-0 @if($errors->has('type')) is-invalid @endif" placeholder="Enter type">
                                    </div>
                                    @if ($errors->has('type'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="description" class="form-label fw-semibold">Description</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-file-list-3-line text-muted"></i>
                                        </span>
                                        <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control border-start-0 ps-0 @if($errors->has('description')) is-invalid @endif" placeholder="Enter description">
                                    </div>
                                    @if ($errors->has('description'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end mt-4 gap-4">
                            <a href="{{ route('shippments.index') }}" class="btn btn-danger px-8">
                                <i class="ri-close-line me-8"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-8">
                                <i class="ri-save-line me-8"></i> Save Shipment
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chosen for select elements
        $("#transit_id, #customer_id, #agent_id").chosen({
            width: "100%",
            no_results_text: "No results match",
            placeholder_text_single: "Select an option"
        });

        // Form validation
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
<style>
    /* Custom styling for Chosen dropdowns */
    .chosen-container {
        width: 100% !important;
    }
    .chosen-container-single .chosen-single {
        height: 38px;
        line-height: 36px;
        border-radius: 0.25rem;
        background: #fff;
        border: 1px solid #ced4da;
        box-shadow: none;
        padding: 0 12px;
    }
    .chosen-container-single .chosen-single div b {
        margin-top: 7px;
    }
    .chosen-container-active .chosen-single {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .chosen-container .chosen-drop {
        border-color: #ced4da;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .chosen-container .chosen-results li.highlighted {
        background: #0d6efd;
    }
</style>
@endpush
