@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $title }}</h4>
            <p class="text-muted small mb-0">Enter container details to create a new record</p>
        </div>
        <a href="{{ route('containers.index') }}" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-8"></i> Back to Containers
        </a>
    </div>

    <!-- Container Form Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex align-items-center">
                <i class="ri-ship-line fs-4 me-4 text-primary"></i>
                <h5 class="card-title mb-0">Container Information</h5>
            </div>
        </div>
        <div class="card-body p-16">
            <form action="{{ route('containers.store') }}" method="post" class="needs-validation" novalidate>
                @csrf

                <!-- Form Content -->
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0"><i class="ri-information-line me-4 text-primary"></i>Basic Details</h6>
                            </div>
                            <div class="card-body p-16">
                                <!-- Container Name/Number -->
                                <div class="mb-4">
                                    <label for="number" class="form-label fw-semibold">Container Name / Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-barcode-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="number" name="number" required placeholder="Enter container number">
                                    </div>
                                    <div class="form-text">Enter the unique container identification number</div>
                                </div>

                                <!-- Category and Size -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="category" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-list-check-2 text-muted"></i>
                                            </span>
                                            <select name="category" id="category" class="form-select border-start-0 ps-0" required>
                                                <option value="" selected disabled>Select category</option>
                                                @foreach($categories as $category)
                                                <option value="{{$category}}">{{$category}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="size" class="form-label fw-semibold">Container Size <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-fullscreen-line text-muted"></i>
                                            </span>
                                            <select name="size" id="size" class="form-select border-start-0 ps-0" required>
                                                <option value="" selected disabled>Select size</option>
                                                <option value="20">20 ft</option>
                                                <option value="40">40 ft</option>
                                                <option value="Open">Open</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Freight Value -->
                                <div class="mb-4">
                                    <label for="entry_number" class="form-label fw-semibold">Freight Value <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-money-dollar-circle-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="entry_number" name="entry_number" required placeholder="Enter freight value">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="card h-100 border shadow-sm">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0"><i class="ri-route-line me-4 text-primary"></i>Transport Details</h6>
                            </div>
                            <div class="card-body p-16">
                                <!-- Departure and Arrival -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="departure_place" class="form-label fw-semibold">Departing From <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-map-pin-line text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 ps-0" id="departure_place" name="departure_place" required placeholder="Enter location name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expected_arrival_time" class="form-label fw-semibold">Expected Arrival <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-calendar-check-line text-muted"></i>
                                            </span>
                                            <input type="date" class="form-control border-start-0 ps-0" id="expected_arrival_time" name="expected_arrival_time" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transporter Info -->
                                <div class="mb-4">
                                    <label for="contact_information" class="form-label fw-semibold">Transporter Name & Tel</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-truck-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="contact_information" name="contact_information" placeholder="Enter transporter details">
                                    </div>
                                </div>

                                <!-- Clearing Agent -->
                                <div class="mb-4">
                                    <label for="clearing_agent" class="form-label fw-semibold">Clearing Agent</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-user-3-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0" id="clearing_agent" name="clearing_agent" placeholder="Enter clearing agent name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end mt-4 gap-4">
                    <!--<button type="reset" class="btn btn-secondary px-16">
                        <i class="ri-refresh-line me-4"></i> Reset
                    </button> -->
                    <button type="submit" class="btn btn-primary px-16">
                        <i class="ri-save-line me-4"></i> Save Container
                    </button>
                </div>
            </form>
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

        // Set min date for expected arrival to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('expected_arrival_time').setAttribute('min', today);
    });
</script>
@endpush
