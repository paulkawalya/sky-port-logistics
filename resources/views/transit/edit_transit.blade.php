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
            <p class="text-muted small mb-0">Update container information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ route('containers.index') }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back to Containers
            </a>
            <a href="{{ route('containers.show', $transit->id) }}" class="btn btn-outline-primary">
                <i class="ri-eye-line me-8"></i> View Details
            </a>
        </div>
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
            <form action="{{ route('containers.update', $transit->id) }}" method="post" class="needs-validation" novalidate>
                @csrf
                @method('PATCH')

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
                                        <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('number')) is-invalid @endif"
                                            id="number" name="number" value="{{ old('number', $transit->number) }}" required placeholder="Enter container number">
                                    </div>
                                    @if ($errors->has('number'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('number') }}</div>
                                    @endif
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
                                            <select name="category" id="category" class="form-select border-start-0 ps-0 @if($errors->has('category')) is-invalid @endif" required>
                                                <option value="" disabled>Select category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category }}" {{ $transit->category == $category ? 'selected' : '' }}>{{ $category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('category'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('category') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="size" class="form-label fw-semibold">Container Size <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-fullscreen-line text-muted"></i>
                                            </span>
                                            <select name="size" id="size" class="form-select border-start-0 ps-0 @if($errors->has('size')) is-invalid @endif" required>
                                                <option value="" disabled>Select size</option>
                                                <option value="20" {{ $transit->size == '20' ? 'selected' : '' }}>20 ft</option>
                                                <option value="40" {{ $transit->size == '40' ? 'selected' : '' }}>40 ft</option>
                                                <option value="Open" {{ $transit->size == 'Open' ? 'selected' : '' }}>Open</option>
                                                @if(!in_array($transit->size, ['20', '40', 'Open']) && $transit->size)
                                                    <option value="{{ $transit->size }}" selected>{{ $transit->size }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        @if ($errors->has('size'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('size') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Freight Value -->
                                <div class="mb-4">
                                    <label for="entry_number" class="form-label fw-semibold">Freight Value <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-money-dollar-circle-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('entry_number')) is-invalid @endif"
                                            id="entry_number" name="entry_number" value="{{ old('entry_number', $transit->entry_number) }}" required placeholder="Enter freight value">
                                    </div>
                                    @if ($errors->has('entry_number'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('entry_number') }}</div>
                                    @endif
                                </div>

                                <!-- Payment Status -->
                                <div class="mb-4">
                                    <label for="payment_status" class="form-label fw-semibold">Payment Status <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-bank-card-line text-muted"></i>
                                        </span>
                                        <select name="payment_status" id="payment_status" class="form-select border-start-0 ps-0 @if($errors->has('payment_status')) is-invalid @endif" required>
                                            <option value="" disabled>Select payment status</option>
                                            @foreach($payments as $payment)
                                                <option value="{{ $payment }}" {{ $transit->payment_status == $payment ? 'selected' : '' }}>{{ ucfirst($payment) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('payment_status'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('payment_status') }}</div>
                                    @endif
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
                                <!-- Departure and Destination -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="departure_place" class="form-label fw-semibold">Departing From <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-map-pin-line text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('departure_place')) is-invalid @endif"
                                                id="departure_place" name="departure_place" value="{{ old('departure_place', $transit->departure_place) }}" required placeholder="Enter location name">
                                        </div>
                                        @if ($errors->has('departure_place'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('departure_place') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="destination_place" class="form-label fw-semibold">Destination <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="ri-map-pin-line text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('destination_place')) is-invalid @endif"
                                                id="destination_place" name="destination_place" value="{{ old('destination_place', $transit->destination_place) }}" required placeholder="Enter destination">
                                        </div>
                                        @if ($errors->has('destination_place'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('destination_place') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Expected Arrival -->
                                <div class="mb-4">
                                    <label for="expected_arrival_time" class="form-label fw-semibold">Expected Arrival <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-calendar-check-line text-muted"></i>
                                        </span>
                                        <input type="date" class="form-control border-start-0 ps-0 @if($errors->has('expected_arrival_time')) is-invalid @endif"
                                            id="expected_arrival_time" name="expected_arrival_time" value="{{ old('expected_arrival_time', $transit->expected_arrival_time) }}" required>
                                    </div>
                                    @if ($errors->has('expected_arrival_time'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('expected_arrival_time') }}</div>
                                    @endif
                                </div>

                                <!-- Transporter Info -->
                                <div class="mb-4">
                                    <label for="contact_information" class="form-label fw-semibold">Transporter Name & Tel</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-truck-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('contact_information')) is-invalid @endif"
                                            id="contact_information" name="contact_information" value="{{ old('contact_information', $transit->contact_information) }}" placeholder="Enter transporter details">
                                    </div>
                                    @if ($errors->has('contact_information'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('contact_information') }}</div>
                                    @endif
                                </div>

                                <!-- Clearing Agent -->
                                <div class="mb-4">
                                    <label for="clearing_agent" class="form-label fw-semibold">Clearing Agent</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="ri-user-3-line text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0 ps-0 @if($errors->has('clearing_agent')) is-invalid @endif"
                                            id="clearing_agent" name="clearing_agent" value="{{ old('clearing_agent', $transit->clearing_agent) }}" placeholder="Enter clearing agent name">
                                    </div>
                                    @if ($errors->has('clearing_agent'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('clearing_agent') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end mt-4 gap-4">
                    <a href="{{ route('containers.index') }}" class="btn btn-light px-8">
                        <i class="ri-close-line me-8"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-8">
                        <i class="ri-save-line me-8"></i> Save Changes
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
    });
</script>
@endpush
