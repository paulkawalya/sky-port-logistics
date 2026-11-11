<span id="exclude">
  @extends('layouts.app')
</span>
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
            <p class="text-muted small mb-0">Invoice details and payment information</p>
        </div>
        <div class="d-flex gap-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="ri-arrow-left-line me-8"></i> Back
            </a>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="invoiceActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-2-fill me-8"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="invoiceActionsDropdown">
                    <li>
                        <button type="button" class="dropdown-item direct-print-button">
                            <i class="ri-printer-line me-8 text-primary"></i> Print Invoice
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item print-button">
                            <i class="ri-file-search-line me-8 text-primary"></i> Print Preview
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#shareOptionsModal">
                            <i class="ri-share-line me-8 text-success"></i> Share
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @if(Auth::user()->canDo(['add_shipment_invoice']))
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_item">
                            <i class="ri-add-line me-8 text-primary"></i> Add Item
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#editCurrencyModal">
                            <i class="ri-exchange-dollar-line me-8 text-primary"></i> Change Currency
                        </button>
                    </li>
                    @endif
                    @if(Auth::user()->canDo(['add_payment_shipment_invoice']))
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_payment">
                            <i class="ri-bank-card-line me-8 text-success"></i> Record Payment
                        </button>
                    </li>
                    @endif
                    <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_applicable_taxes">
                            <i class="ri-percent-line me-8 text-warning"></i> Add Taxes
                        </button>
                    </li>
                    @if(Auth::user()->canDo(['delete_shipment_invoice']))
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('invoices.destroy',$invoice->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ri-delete-bin-line me-8"></i> Delete Invoice
                            </button>
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Invoice Content -->
    <div class="row">
        <!-- Invoice Details Card -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-file-list-3-line me-4 text-primary"></i>Invoice Details
                        </h5>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                            {{ $invoice->transaction_currency }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-16">
                    <!-- Invoice Header -->
                    <div class="text-center mb-8">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/sifcargo.png'))) }}" alt="SIFCARGO TONIGHT" style="width: auto; max-width: 380px; max-height: 180px; margin: 0 auto 16px; display: block;">
                        <h4 class="fw-bold mb-0">INVOICE</h4>
                        <div class="border-bottom border-2 border-primary w-25 mx-auto mt-2 mb-4"></div>
                    </div>

                    <!-- Invoice Info Section -->
                    <div class="row g-4 mb-8">
                        <!-- Customer Info -->
                        <div class="col-md-4">
                            <div class="p-8 rounded bg-light">
                                <h6 class="fw-bold mb-4 text-uppercase">Consignee</h6>
                                <div class="d-flex align-items-center mb-4">
                                    <div class="icon-box me-4 flex-shrink-0" style="width: 40px; height: 40px; border-radius: 8px; background-color: rgba(13, 110, 253, 0.1); display: flex; align-items: center; justify-content: center;">
                                        <i class="ri-user-3-line text-primary" style="font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium">{{$invoice->shippment->customer->name}}</p>
                                        <p class="mb-0 small text-muted">{{$invoice->shippment->customer->phone_number}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Info -->
                        <div class="col-md-4">
                            <div class="p-8 rounded bg-light">
                                <h6 class="fw-bold mb-4 text-uppercase">Invoice Information</h6>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Invoice No:</span>
                                    <span class="fw-medium">{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Date:</span>
                                    <span class="fw-medium">{{Carbon\Carbon::parse($invoice->created_at)->format("d M Y")}}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Currency:</span>
                                    <span class="fw-medium">{{$invoice->transaction_currency}}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Container Info -->
                        <div class="col-md-4">
                            <div class="p-8 rounded bg-light">
                                <h6 class="fw-bold mb-4 text-uppercase">Shipment Details</h6>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Container No:</span>
                                    <span class="fw-medium">{{ $invoice->shippment->transit->number }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="text-muted small">Volume:</span>
                                    <span class="fw-medium">{{ $invoice->shippment->volume." ".$invoice->shippment->unit_of_measure }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Packages:</span>
                                    <span class="fw-medium">{{ $invoice->shippment->bl_packages }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items Section -->
                    <div class="mb-8">
                        <h6 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="ri-list-check-3 me-4 text-primary"></i>Invoice Items
                            @if(Auth::user()->canDo(['add_shipment_invoice']))
                            <button type="button" class="btn btn-sm btn-outline-primary ms-auto" data-toggle="modal" data-target="#add_item">
                                <i class="ri-add-line me-8"></i> Add Item
                            </button>
                            @endif
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="40%">Description</th>
                                        <th width="15%">Quantity</th>
                                        <th width="15%">Unit Price</th>
                                        <th width="15%">Amount</th>
                                        @if(Auth::user()->canDo(['edit_shipment_invoice']))
                                        <th width="10%" class="text-end">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->details as $row => $detail)
                                    <tr>
                                        <td>{{$row+1}}</td>
                                        <td>{{ $detail->particular }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->unit_price, 0) }}</td>
                                        <td>{{ number_format($detail->quantity * $detail->unit_price, 0) }}</td>
                                        @if(Auth::user()->canDo(['edit_shipment_invoice']))
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-4">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-icon" data-toggle="modal" data-target="#edit_item{{$detail->id}}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('invoice_details.destroy', $detail->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-icon">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @include("models.edit_item")
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payments Section -->
                    <div class="mb-8">
                        <h6 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="ri-bank-card-line me-4 text-success"></i>Payment History
                            @if(Auth::user()->canDo(['add_payment_shipment_invoice']))
                            <button type="button" class="btn btn-sm btn-outline-success ms-auto" data-toggle="modal" data-target="#add_payment">
                                <i class="ri-add-line me-8"></i> Record Payment
                            </button>
                            @endif
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle compact-table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="py-2">Date</th>
                                        <th scope="col" class="py-2">Amount</th>
                                        <th scope="col" class="py-2">Payment Method</th>
                                        <th scope="col" class="py-2">Recorded By</th>
                                        @if(Auth::user()->canDo(['edit_shipment_invoice']))
                                        <th scope="col" class="py-2 text-end">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($invoice->payments->count() > 0)
                                        @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td class="py-1">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                            <td class="py-1">{{ number_format($payment->amount_paid, 0) }} {{ $invoice->transaction_currency }}</td>
                                            <td class="py-1">
                                                @php
                                                    $modeIcons = [
                                                        'Cash' => 'ri-money-dollar-circle-line text-success',
                                                        'Bank' => 'ri-bank-line text-primary',
                                                        'Mobile Money' => 'ri-smartphone-line text-warning',
                                                        'Digital' => 'ri-global-line text-info'
                                                    ];
                                                    $icon = $modeIcons[$payment->mode_of_payment] ?? 'ri-money-dollar-circle-line';
                                                @endphp
                                                <i class="{{ $icon }} me-2"></i>
                                                <span class="small">{{ $payment->mode_of_payment }}</span>
                                            </td>
                                            <td class="py-1 small">{{ $payment->user->name }}</td>
                                            @if(Auth::user()->canDo(['edit_shipment_invoice']))
                                            <td class="py-1 text-end">
                                                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-icon">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="{{ Auth::user()->canDo(['edit_shipment_invoice']) ? '5' : '4' }}" class="text-center py-3">
                                                <div class="text-muted small">
                                                    <i class="ri-information-line me-2"></i>No payments recorded yet
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Amount in Words Section -->
                    <div class="mb-8">
                        <div class="p-8 rounded bg-light">
                            <h6 class="fw-bold mb-4 text-uppercase">Amount in Words</h6>
                            <p class="mb-0 text-uppercase text-danger fw-medium">
                                {{ \App\Models\Invoice::numberToWords(round($totalAmount)) }} {{ $invoice->transaction_currency ?? 'UGX' }} ONLY
                            </p>
                        </div>
                    </div>

                    <!-- Signatures Section -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-4 text-uppercase">
                            <i class="ri-user-follow-line me-4 text-primary"></i>Authorization
                        </h6>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="p-8 rounded bg-light text-center">
                                    <p class="text-muted small mb-4">Processed by</p>
                                    <div class="mb-4">
                                        <span class="badge bg-primary-subtle text-primary px-4 py-2">{{$invoice->user->name}}</span>
                                    </div>
                                    <div class="border-top pt-4">
                                        <p class="mb-0 small text-muted">Signature</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-8 rounded bg-light text-center">
                                    <p class="text-muted small mb-4">Verified by</p>
                                    <div class="mb-4">
                                        <span class="badge bg-secondary-subtle text-secondary px-4 py-2">Accountant</span>
                                    </div>
                                    <div class="border-top pt-4">
                                        <p class="mb-0 small text-muted">Signature</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-8 rounded bg-light text-center">
                                    <p class="text-muted small mb-4">Approved by</p>
                                    <div class="mb-4">
                                        <span class="badge bg-secondary-subtle text-secondary px-4 py-2">Manager</span>
                                    </div>
                                    <div class="border-top pt-4">
                                        <p class="mb-0 small text-muted">Signature</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Summary Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px; z-index: 100;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="ri-calculator-line me-4 text-primary"></i>Invoice Summary
                    </h5>
                </div>
                <div class="card-body p-16">
                    <!-- Payment Status -->
                    <div class="text-center mb-8">
                        @php
                            $statusClass = 'bg-danger-subtle text-danger';
                            $statusText = 'Unpaid';
                            $statusIcon = 'ri-error-warning-line';

                            if ($balance == 0) {
                                $statusClass = 'bg-success-subtle text-success';
                                $statusText = 'Paid';
                                $statusIcon = 'ri-checkbox-circle-line';
                            } elseif ($balance < $totalAmount) {
                                $statusClass = 'bg-warning-subtle text-warning';
                                $statusText = 'Partially Paid';
                                $statusIcon = 'ri-time-line';
                            }
                        @endphp
                        <div class="mb-4">
                            <span class="badge {{ $statusClass }} px-4 py-2 fs-6">
                                <i class="{{ $statusIcon }} me-4"></i>{{ $statusText }}
                            </span>
                        </div>

                        @if($balance > 0 && Auth::user()->canDo(['add_payment_shipment_invoice']))
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#add_payment">
                            <i class="ri-bank-card-line me-8"></i> Make Payment
                        </button>
                        @endif
                    </div>

                    <!-- Summary Details -->
                    <div class="p-8 rounded bg-light mb-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-medium">{{ number_format($amount, 0) }} {{ $invoice->transaction_currency }}</span>
                        </div>

                        @if($invoice->taxinvoices->count() > 0)
                            @foreach($invoice->taxinvoices as $taxinvoice)
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="text-muted d-flex align-items-center">
                                    {{ $taxinvoice->tax->tax_name }}:
                                    @if(Auth::user()->canDo(['delete_taxes']))
                                    <form action="{{ route('invoice_taxes.destroy', $taxinvoice->id) }}" method="post" class="ms-4" onsubmit="return confirm('Are you sure you want to delete this tax record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                    @endif
                                </span>
                                <span class="fw-medium">{{ number_format($taxinvoice->amount($taxinvoice->id), 0) }} {{ $invoice->transaction_currency }}</span>
                            </div>
                            @endforeach
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">Tax Amount:</span>
                            <span class="fw-medium">{{ number_format($taxAmount, 0) }} {{ $invoice->transaction_currency }}</span>
                        </div>

                        <div class="border-top my-4"></div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-semibold">Total:</span>
                            <span class="fw-bold fs-5">{{ number_format($totalAmount, 0) }} {{ $invoice->transaction_currency }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted">Amount Paid:</span>
                            <span class="fw-medium text-success">{{ number_format($paidAmount, 0) }} {{ $invoice->transaction_currency }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Balance Due:</span>
                            <span class="fw-bold fs-5 {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format($balance, 0) }} {{ $invoice->transaction_currency }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-4 text-uppercase">Quick Actions</h6>
                        <div class="d-grid gap-4">
                            <button type="button" class="btn btn-outline-primary direct-print-button">
                                <i class="ri-printer-line me-8"></i> Print Invoice
                            </button>
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#shareOptionsModal">
                                <i class="ri-share-line me-8"></i> Share Invoice
                            </button>
                            @if(Auth::user()->canDo(['add_shipment_invoice']))
                            <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#add_applicable_taxes">
                                <i class="ri-percent-line me-8"></i> Add Taxes
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include("models.payments")
@include("models.edit_currency")
@include("models.add_invoice_item")
@include("models.add_invoice_tax")

<!-- Print Preview Modal -->
<div class="modal fade" id="printPreviewModal" tabindex="-1" role="dialog" aria-labelledby="printPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printPreviewModalLabel">
                    <i class="ri-file-search-line me-4 text-primary"></i>Print Preview
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="printPreviewFrame" src="{{ route('invoices.print', $invoice->id) }}" style="width: 100%; height: 70vh; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="ri-close-line me-8"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="printInvoice()">
                    <i class="ri-printer-line me-8"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Share Options Modal -->
<div class="modal fade" id="shareOptionsModal" tabindex="-1" role="dialog" aria-labelledby="shareOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareOptionsModalLabel">
                    <i class="ri-share-line me-4 text-success"></i>Share Invoice
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mb-4">
                        <span class="badge bg-primary-subtle text-primary px-4 py-2 fs-6">
                            Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <p class="text-muted">Choose how you want to share this invoice</p>
                </div>

                <div class="alert alert-light border-start border-4 border-info">
                    <div class="d-flex">
                        <i class="ri-information-line fs-4 me-4 text-info"></i>
                        <p class="mb-0">The secure PDF link can be shared with anyone. No login required - recipients will get a direct PDF download.</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-16">
                                <div class="icon-box mx-auto mb-4" style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(25, 135, 84, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="ri-link text-success" style="font-size: 28px;"></i>
                                </div>
                                <h5 class="mb-4">Secure PDF Link</h5>
                                <p class="small text-muted mb-4">Generate a secure PDF download link</p>
                                <button type="button" onclick="copyShareLink()" class="btn btn-outline-success w-100">
                                    <i class="ri-clipboard-line me-8"></i>Copy Link
                                </button>
                                <div id="copyShareMessage" class="small text-success mt-2 d-none">
                                    <i class="ri-check-line me-4"></i>Link copied!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-16">
                                <div class="icon-box mx-auto mb-4" style="width: 60px; height: 60px; border-radius: 50%; background-color: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 28px;"></i>
                                </div>
                                <h5 class="mb-4">Download PDF</h5>
                                <p class="small text-muted mb-4">Save as PDF document</p>
                                <a href="{{ route('invoices.public.pdf', \App\Helpers\IdHasher::encode($invoice->id)) }}" class="btn btn-outline-danger w-100">
                                    <i class="ri-download-line me-8"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <i class="ri-close-line me-8"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden iframe for direct printing -->
<iframe id="hiddenPrintFrame" style="position: fixed; right: -9999px; bottom: -9999px; width: 0; height: 0; border: 0;"></iframe>

@endsection


@section('styles')
<style>
    @media print {
        #exclude, a, nav, #hide_form, .hp-sidebar, header {
            display: none;
        }
    }

    /* Sticky summary card */
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky;
            top: 80px;
            z-index: 1000;
        }
    }

    /* Payment status badges */
    .badge.bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
        color: #198754 !important;
    }

    .badge.bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
        color: #ffc107 !important;
    }

    .badge.bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
    }

    .badge.bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
        color: #0d6efd !important;
    }

    .badge.bg-secondary-subtle {
        background-color: rgba(108, 117, 125, 0.1) !important;
        color: #6c757d !important;
    }

    /* Table styles */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    /* Compact table styles */
    .compact-table th,
    .compact-table td {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
        font-size: 0.875rem !important;
    }

    .compact-table .badge {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
    }

    .compact-table .btn-sm {
        padding: 0.1rem 0.5rem !important;
        font-size: 0.75rem !important;
    }

    /* Icon button styles */
    .btn-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0 !important;
    }

    .btn-icon i {
        font-size: 1rem;
        line-height: 1;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize chosen selects
        $("#mode_of_payment, #payment_account_id").chosen({
            width: "100%",
            no_results_text: "No results match",
            allow_single_deselect: true
        });

        // Print preview modal
        $('.print-button').click(function() {
            $('#printPreviewModal').modal('show');
        });

        // Direct print button
        $('.direct-print-button').click(function() {
            printDirectly();
        });

        // Initialize Bootstrap tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle mode of payment change
        $('#mode_of_payment').on('change', function(e) {
            var mode_of_payment = e.target.value;
            var currency = "{!! $invoice->transaction_currency !!}";

            $.get('/get_accounts/' + mode_of_payment + '/' + currency, function(data) {
                $("#payment_account_id").chosen('destroy');
                $("#payment_account_id").empty();
                $('#payment_account_id').append("<option value=''>Select account</option>");

                $.each(data, function(index, account) {
                    $('#payment_account_id').append("<option value=" + account.id + ">" + account.name + " " + account.account_number + " " + account.transaction_currency + "</option>");
                });

                $("#payment_account_id").chosen({
                    width: "100%"
                });
            });
        });
    });

    // Print invoice from preview modal
    function printInvoice() {
        var frame = document.getElementById('printPreviewFrame');
        frame.contentWindow.print();
    }

    // Print directly without preview
    function printDirectly() {
        // Use existing hidden iframe
        let printFrame = document.getElementById('hiddenPrintFrame');

        // Load the print page into the iframe
        printFrame.src = '{{ route("invoices.print", $invoice->id) }}?print=true';

        // Wait for the iframe to load, then print it
        printFrame.onload = function() {
            setTimeout(function() {
                try {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                } catch (e) {
                    console.error('Printing failed:', e);
                    // Fallback to opening in a new tab
                    window.open('{{ route("invoices.print", $invoice->id) }}?print=true', '_blank');
                }
            }, 500);
        };
    }

    // Copy share link functionality
    function copyShareLink() {
        // Use IdHasher directly to ensure consistent encryption
        const secureUrl = '{{ route("invoices.public.pdf", \App\Helpers\IdHasher::encode($invoice->id)) }}';

        // Try to use the modern Clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(secureUrl)
                .then(() => {
                    // Show success message
                    const copyMessage = document.getElementById("copyShareMessage");
                    copyMessage.classList.remove("d-none");

                    // Hide message after 3 seconds
                    setTimeout(function() {
                        copyMessage.classList.add("d-none");
                    }, 3000);
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                    // Fallback to the old method
                    fallbackCopyTextToClipboard(secureUrl);
                });
        } else {
            // Fallback for browsers that don't support the Clipboard API
            fallbackCopyTextToClipboard(secureUrl);
        }
    }

    // Fallback copy method
    function fallbackCopyTextToClipboard(text) {
        // Create a temporary input element
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Show success message
        const copyMessage = document.getElementById("copyShareMessage");
        copyMessage.classList.remove("d-none");

        // Hide message after 3 seconds
        setTimeout(function() {
            copyMessage.classList.add("d-none");
        }, 3000);
    }
</script>
@endpush
