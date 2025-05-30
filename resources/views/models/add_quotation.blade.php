<div id="add_new_quotation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addQuotationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-opacity-10 py-3">
                <h5 class="modal-title fw-bold" id="addQuotationModalLabel">
                    <i class="ri-file-list-3-line me-8 text-primary"></i><span id="modalTitleText">Create New Document</span>
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-32">
                <form id="quotationForm" action="{{ route('quotations.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('POST')

                    <!-- Document Type Selection -->
                    <div class="mb-8">
                        <div class="btn-group w-100" role="group" aria-label="Document Type">
                            <input type="radio" class="btn-check" name="transaction_type" id="type_invoice" value="invoice" checked>
                            <label class="btn btn-outline-primary" for="type_invoice">
                                <i class="ri-bill-line me-8"></i>Invoice
                            </label>

                            <input type="radio" class="btn-check" name="transaction_type" id="type_quotation" value="quotation">
                            <label class="btn btn-outline-primary" for="type_quotation">
                                <i class="ri-file-paper-2-line me-8"></i>Quotation
                            </label>
                        </div>
                    </div>

                    <!-- Main Form Content -->
                    <div class="row g-4">
                        <!-- Left Column - Required Fields -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-8">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="ri-information-line me-8 text-primary"></i>Basic Information
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <!-- Customer -->
                                    <div class="mb-8">
                                        <label for="customer_id" class="form-label fw-semibold small d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="ri-user-search-line me-8 text-primary"></i>
                                                Customer <span class="text-danger">*</span>
                                            </div>
                                            <a href="#" class="text-primary small" data-toggle="modal" data-target="#addCustomerModal">
                                                <i class="ri-user-add-line me-8"></i>New customer?
                                            </a>
                                        </label>
                                        <select name="customer_id" id="customer_id" class="form-select chosen-select" required>
                                            <option value="">Select a customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Date -->
                                    <div class="mb-8">
                                        <label for="date_quoted" class="form-label fw-semibold small">
                                            <i class="ri-calendar-line me-8 text-primary"></i>
                                            Date <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="date_quoted" id="date_quoted" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <!-- Currency -->
                                    <div class="mb-0">
                                        <label for="transaction_currency" class="form-label fw-semibold small">
                                            <i class="ri-money-dollar-circle-line me-8 text-primary"></i>
                                            Currency <span class="text-danger">*</span>
                                        </label>
                                        <select name="transaction_currency" id="transaction_currency" class="form-select" required>
                                            @foreach($transaction_currencies as $transaction_currency)
                                                <option value="{{ $transaction_currency }}" {{ $transaction_currency == 'USD' ? 'selected' : '' }}>{{ $transaction_currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Optional Fields -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-8">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="ri-truck-line me-8 text-primary"></i>Shipment Details
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <!-- Container/Waybill No -->
                                    <div class="mb-8">
                                        <label for="container_air_no" class="form-label fw-semibold small">
                                            <i class="ri-ship-line me-8 text-primary"></i>
                                            Container / Waybill No
                                        </label>
                                        <input type="text" name="container_air_no" id="container_air_no" class="form-control">
                                    </div>

                                    <!-- Entry No -->
                                    <div class="mb-8">
                                        <label for="entry_no" class="form-label fw-semibold small">
                                            <i class="ri-numbers-line me-8 text-primary"></i>
                                            Entry No
                                        </label>
                                        <input type="text" name="entry_no" id="entry_no" class="form-control">
                                    </div>

                                    <!-- Dimensions Row -->
                                    <div class="row g-2 mb-0">
                                        <!-- Weight -->
                                        <div class="col-4">
                                            <label for="weight" class="form-label fw-semibold small">
                                                <i class="ri-scales-line me-8 text-primary"></i>
                                                Weight (KG)
                                            </label>
                                            <input type="number" step="0.001" name="weight" id="weight" class="form-control">
                                        </div>

                                        <!-- Volume -->
                                        <div class="col-4">
                                            <label for="volume" class="form-label fw-semibold small">
                                                <i class="ri-box-3-line me-8 text-primary"></i>
                                                Volume
                                            </label>
                                            <input type="number" step="0.001" name="volume" id="volume" class="form-control">
                                        </div>

                                        <!-- Packages -->
                                        <div class="col-4">
                                            <label for="packages" class="form-label fw-semibold small">
                                                <i class="ri-archive-line me-8 text-primary"></i>
                                                Packages
                                            </label>
                                            <input type="number" name="packages" id="packages" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Remarks - Full Width -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="ri-chat-quote-line me-8 text-primary"></i>Additional Information
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-0">
                                        <label for="description" class="form-label fw-semibold small">Remarks</label>
                                        <textarea name="description" id="description" class="form-control" rows="2" placeholder="Enter any additional notes or details"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-4 mt-8">
                        <button type="button" class="btn btn-danger px-24 py-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-24 py-8" id="submitBtn">
                            <span id="submitText">
                                <i class="ri-save-line me-8"></i>
                                <span id="submitButtonText">Create Document</span>
                            </span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to radio buttons and elements to update
        const invoiceRadio = document.getElementById('type_invoice');
        const quotationRadio = document.getElementById('type_quotation');
        const modalTitleText = document.getElementById('modalTitleText');
        const submitButtonText = document.getElementById('submitButtonText');

        // Function to update UI based on selected document type
        function updateDocumentTypeUI(isInvoice) {
            if (isInvoice) {
                modalTitleText.textContent = 'Create New Invoice';
                submitButtonText.textContent = 'Create Invoice';

                // Update button styles
                invoiceRadio.nextElementSibling.classList.add('active', 'btn-primary');
                invoiceRadio.nextElementSibling.classList.remove('btn-outline-primary');

                quotationRadio.nextElementSibling.classList.remove('active', 'btn-primary');
                quotationRadio.nextElementSibling.classList.add('btn-outline-primary');
            } else {
                modalTitleText.textContent = 'Create New Quotation';
                submitButtonText.textContent = 'Create Quotation';

                // Update button styles
                quotationRadio.nextElementSibling.classList.add('active', 'btn-primary');
                quotationRadio.nextElementSibling.classList.remove('btn-outline-primary');

                invoiceRadio.nextElementSibling.classList.remove('active', 'btn-primary');
                invoiceRadio.nextElementSibling.classList.add('btn-outline-primary');
            }
        }

        // Set initial state (invoice is checked by default)
        updateDocumentTypeUI(true);

        // Add event listeners to radio buttons
        invoiceRadio.addEventListener('change', function() {
            if (this.checked) {
                updateDocumentTypeUI(true);
            }
        });

        quotationRadio.addEventListener('change', function() {
            if (this.checked) {
                updateDocumentTypeUI(false);
            }
        });

        // Fix Chosen.js styling issues
        setTimeout(function() {
            const chosenContainers = document.querySelectorAll('.chosen-container');
            chosenContainers.forEach(container => {
                // Remove any incorrectly displayed icons
                const icons = container.querySelectorAll('.ri-user-search-line');
                icons.forEach(icon => icon.remove());

                // Adjust the width to match the select element
                container.style.width = '100%';
            });

            // Add some styling to chosen containers
            $('.chosen-container').addClass('rounded');
            $('.chosen-single').addClass('h-100 d-flex align-items-center');
        }, 100);
    });
</script>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-opacity-10 py-3">
                <h5 class="modal-title fw-bold" id="addCustomerModalLabel">
                    <i class="ri-user-add-line me-8 text-primary"></i>Add New Customer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-24">
                <div class="card border-0 shadow-sm mb-0">
                    <div class="card-body p-3">
                        <form id="addCustomerForm" class="row g-3" onsubmit="return false;">
                            @csrf
                            <div class="col-md-12">
                                <label for="new_name" class="form-label fw-semibold small">
                                    <i class="ri-user-line me-8 text-primary"></i>Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="name" id="new_name" required>
                                <div class="invalid-feedback">Please enter a name</div>
                            </div>
                            <div class="col-md-12">
                                <label for="new_phone_number" class="form-label fw-semibold small">
                                    <i class="ri-phone-line me-8 text-primary"></i>Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="phone_number" id="new_phone_number" required>
                                <div class="invalid-feedback">Please enter a phone number</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-3">
                <button type="button" class="btn btn-danger px-16" data-dismiss="modal">
                    <i class="ri-close-line me-8"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary px-16" id="saveCustomerBtn">
                    <i class="ri-save-line me-8"></i> Save Customer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/quotation-form.js') }}"></script>
<script>
    $(document).ready(function() {
        // Set up AJAX CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Handle modal events to prevent scrolling
        let savedScrollPosition = 0;
        $('#addCustomerModal').on('show.bs.modal', function() {
            savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        });

        $('#addCustomerModal').on('hidden.bs.modal', function() {
            window.scrollTo(0, savedScrollPosition);
        });

        // Prevent form submission when pressing Enter in the modal inputs
        $('#addCustomerForm input').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if ($(this).attr('id') === 'new_name') {
                    $('#new_phone_number').focus();
                } else if ($(this).attr('id') === 'new_phone_number') {
                    $('#saveCustomerBtn').click();
                }
                return false;
            } else if (e.key === 'Escape') {
                forceCloseModal('#addCustomerModal');
            }
        });

        // Add keyboard shortcut to close modal with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#addCustomerModal').hasClass('show')) {
                forceCloseModal('#addCustomerModal');
            }
        });

        // Initialize chosen select
        $("#customer_id").chosen({
            width: "100%",
            no_results_text: "No customers found",
            placeholder_text_single: "Select a customer"
        });

        // Initialize other selects
        $("#transaction_currency").chosen({
            width: "100%"
        });

        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Load SweetAlert2 if not already loaded
        if (typeof Swal === 'undefined') {
            $.getScript('/assets/libs/sweetalert2/sweetalert2.min.js');
        }

        // Function to properly close modal
        function forceCloseModal(modalId) {
            $(modalId).modal('hide');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');
        }

        // Handle Add Customer form submission
        $('#saveCustomerBtn').on('click', function(e) {
            // Prevent default behavior
            e.preventDefault();
            e.stopPropagation();

            // Save current scroll position
            const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

            const form = document.getElementById('addCustomerForm');
            const name = $('#new_name').val();
            const phoneNumber = $('#new_phone_number').val();

            // Basic validation
            if (!name || !phoneNumber) {
                if (!name) {
                    $('#new_name').addClass('is-invalid');
                } else {
                    $('#new_name').removeClass('is-invalid');
                }

                if (!phoneNumber) {
                    $('#new_phone_number').addClass('is-invalid');
                } else {
                    $('#new_phone_number').removeClass('is-invalid');
                }
                return;
            }

            // Show loading state
            const saveBtn = $(this);
            const originalText = saveBtn.html();
            saveBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            saveBtn.prop('disabled', true);

            // Send AJAX request
            $.ajax({
                url: '{{ route('customers.store') }}',
                type: 'POST',
                data: {
                    name: name,
                    phone_number: phoneNumber
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Add new customer to select dropdown
                        const newOption = new Option(response.customer.name, response.customer.id, true, true);
                        $('#customer_id').append(newOption);

                        // Update chosen dropdown
                        $('#customer_id').val(response.customer.id);
                        $('#customer_id').trigger('chosen:updated');

                        // Reset form
                        $('#addCustomerForm')[0].reset();

                        // Force close the modal
                        forceCloseModal('#addCustomerModal');

                        // Restore scroll position after modal is hidden
                        setTimeout(function() {
                            window.scrollTo(0, scrollPosition);
                        }, 100);

                        // Show success message using alert if SweetAlert is not available
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonColor: '#0d6efd'
                            });
                        } else {
                            alert('Customer created successfully!');
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to create customer',
                                confirmButtonColor: '#0d6efd'
                            });
                        } else {
                            alert('Failed to create customer');
                        }
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while saving the customer';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors)[0][0];
                    }

                    // Force close the modal
                    forceCloseModal('#addCustomerModal');

                    // Restore scroll position
                    setTimeout(function() {
                        window.scrollTo(0, scrollPosition);
                    }, 100);

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#0d6efd'
                        });
                    } else {
                        alert(errorMessage);
                    }
                },
                complete: function() {
                    // Reset button state
                    saveBtn.html(originalText);
                    saveBtn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
