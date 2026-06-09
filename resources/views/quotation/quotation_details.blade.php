<span id="exclude">
  @extends('layouts.app')
</span>
@section('content')

<div class="container-fluid px-4 py-4">
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-8">
          <!-- Header Section -->
          <div class="bg-light border-bottom p-8">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <div class="me-8">
                  <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded-circle">
                    <i class="ri-file-list-3-line fs-4"></i>
                  </div>
                </div>
                <div>
                  <h4 class="mb-0">{{ ucfirst($quotation->transaction_type) }} #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</h4>
                  <p class="mb-0 text-muted">
                    <i class="ri-calendar-line me-8"></i> Created on {{ Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}
                  </p>
                </div>
              </div>

              <div class="d-flex gap-8 align-items-center">
                <!-- Status Badge -->
                <div class="d-none d-md-block">
                  @if($quotation->transaction_type == "invoice")
                    @if($quotation->balance($quotation->id) <= 0)
                      <span class="badge bg-success px-3 py-2 rounded-pill"><i class="ri-checkbox-circle-line me-8"></i> Paid</span>
                    @else
                      <span class="badge bg-warning px-3 py-2 rounded-pill"><i class="ri-time-line me-8"></i> Pending Payment</span>
                    @endif
                  @else
                    <span class="badge bg-info px-3 py-2 rounded-pill"><i class="ri-file-list-3-line me-8"></i> Quotation</span>
                  @endif
                </div>

                <!-- Action Buttons -->
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-primary direct-print-button">
                    <i class="ri-printer-line me-8"></i> Print
                  </button>
                  <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a href="{{ route('quotations.public.pdf', \App\Helpers\IdHasher::encode($quotation->id)) }}" class="dropdown-item">
                        <i class="ri-download-line me-8"></i> Download PDF
                      </a>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item print-button">
                        <i class="ri-printer-line me-8"></i> Print Preview
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#shareOptionsModal">
                        <i class="ri-share-line me-8"></i> Share
                      </button>
                    </li>
                  </ul>
                </div>

                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="ri-add-line me-8"></i> Actions
                  </button>
                  <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    @if(Auth::user()->canDo(['create_quotations']))
                      <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_item">
                          <i class="ri-add-line me-8 text-success"></i> Add Item
                        </button>
                      </li>
                    @endif

                    <li>
                      <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_applicable_taxes">
                        <i class="ri-percent-line me-8 text-warning"></i> Add Taxes
                      </button>
                    </li>

                    @if($quotation->transaction_type=="invoice")
                      <li>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#add_payment">
                          <i class="ri-money-dollar-circle-line me-8 text-success"></i> Record Payment
                        </button>
                      </li>
                      @if($quotation->balance($quotation->id) > 0)
                      <li>
                        <form action="{{ route('quotations.mark-as-paid', $quotation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this invoice as fully paid?');">
                          @csrf
                          <button type="submit" class="dropdown-item">
                            <i class="ri-checkbox-circle-line me-8 text-success"></i> Mark as Paid
                          </button>
                        </form>
                      </li>
                      @endif
                    @endif

                    @if(Auth::user()->canDo(['delete_quotations']))
                      <li><hr class="dropdown-divider"></li>
                      <li>
                        <form action="{{ route('quotations.destroy',$quotation->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this {{ $quotation->transaction_type }}?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item text-danger">
                            <i class="ri-delete-bin-line me-8"></i> Delete {{ ucfirst($quotation->transaction_type) }}
                          </button>
                        </form>
                      </li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Document Header -->
          <div class="p-8 border-bottom bg-gradient">
            <div class="text-center mb-3">
              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/skyport.png'))) }}" alt="SKY PORT LOGISTICS LTD" style="width: auto; max-height: 120px;">
            </div>
            <div class="text-center">
              <div class="d-inline-block p-8 bg-white rounded shadow-sm">
                <h3 class="text-uppercase fw-bold mb-0 text-primary">{{ $quotation->transaction_type }}</h3>
              </div>
            </div>
          </div>

          <!-- Information Sections - Compact Design -->
          <div class="p-8">
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body p-8">
                <div class="row g-0">
                  <!-- Customer Information -->
                  <div class="col-md-4 border-end">
                    <div class="p-8">
                      <div class="d-flex align-items-center mb-2">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-8">
                          <i class="ri-user-3-line"></i>
                        </div>
                        <h6 class="mb-0 text-primary small">Customer</h6>
                      </div>

                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle me-8">
                          <span class="small">{{ substr($quotation->customer->name, 0, 1) }}</span>
                        </div>
                        <div>
                          <p class="mb-0 fw-medium small">{{$quotation->customer->name}}</p>
                          <p class="mb-0 text-muted small">{{$quotation->customer->phone_number}}</p>
                        </div>
                      </div>

                      @if($quotation->description)
                      <div class="p-8 border-top">
                        <p class="mb-1 text-muted"><i class="ri-chat-quote-line me-8 text-primary"></i> Remarks</p>
                        <p class="mb-0 small">{{$quotation->description}}</p>
                      </div>
                      @endif
                    </div>
                  </div>

                  <!-- Shipment Details -->
                  <div class="col-md-4 border-end">
                    <div class="p-8">
                      <div class="d-flex align-items-center mb-2">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-8">
                          <i class="ri-ship-line"></i>
                        </div>
                        <h6 class="mb-0 text-primary small">Shipment</h6>
                      </div>

                      <div class="row g-8">
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Entry No</p>
                            <p class="mb-0 small fw-medium text-truncate" title="{{$quotation->entry_no ?: 'N/A'}}">
                              <i class="ri-numbers-line text-primary me-8"></i>
                              {{$quotation->entry_no ?: 'N/A'}}
                            </p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Container</p>
                            <p class="mb-0 small fw-medium text-truncate" title="{{$quotation->container_air_no ?: 'N/A'}}">
                              <i class="ri-ship-line text-primary me-8"></i>
                              {{$quotation->container_air_no ?: 'N/A'}}
                            </p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Weight</p>
                            <p class="mb-0 small fw-medium">
                              <i class="ri-scales-line text-primary me-8"></i>
                              {{$quotation->weight ? $quotation->weight.' KG' : 'N/A'}}
                            </p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Volume</p>
                            <p class="mb-0 small fw-medium">
                              <i class="ri-box-3-line text-primary me-8"></i>
                              {{$quotation->volume ? $quotation->volume.' M³' : 'N/A'}}
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Document Details -->
                  <div class="col-md-4">
                    <div class="p-8">
                      <div class="d-flex align-items-center mb-2">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-8">
                          <i class="ri-file-list-line"></i>
                        </div>
                        <h6 class="mb-0 text-primary small">Document</h6>
                      </div>

                      <div class="row g-8">
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Number</p>
                            <div class="d-flex align-items-center">
                              <i class="ri-hashtag text-primary me-8"></i>
                              <p class="mb-0 small fw-medium me-8">{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</p>
                              <span class="badge bg-primary bg-opacity-10 text-primary small">{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Date</p>
                            <p class="mb-0 small fw-medium">
                              <i class="ri-calendar-line text-primary me-8"></i>
                              {{Carbon\Carbon::parse($quotation->created_at)->format("d M Y")}}
                            </p>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Currency</p>
                            <div class="d-flex align-items-center">
                              <i class="ri-money-dollar-circle-line text-primary me-8"></i>
                              <p class="mb-0 small fw-medium me-8">{{$quotation->transaction_currency}}</p>
                              <span class="badge bg-success bg-opacity-10 text-white small">{{$quotation->transaction_currency}}</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="bg-light rounded p-8">
                            <p class="mb-0 small text-muted">Packages</p>
                            <p class="mb-0 small fw-medium">
                              <i class="ri-archive-line text-primary me-8"></i>
                              {{$quotation->packages ?: 'N/A'}}
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
              <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="items-tab" data-bs-toggle="tab" data-bs-target="#items-tab-pane" type="button" role="tab" aria-controls="items-tab-pane" aria-selected="true">Items & Services</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-tab-pane" type="button" role="tab" aria-controls="payments-tab-pane" aria-selected="false">Payment History</button>
                </li>
              </ul>
            </div>
            <div class="card-body p-0">
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="items-tab-pane" role="tabpanel" aria-labelledby="items-tab" tabindex="0">
                  <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                      <thead class="bg-light">
                        <tr>
                          <th class="text-center border-0" width="5%">#</th>
                          <th class="border-0" width="45%">Description</th>
                          <th class="text-center border-0" width="10%">Quantity</th>
                          <th class="text-end border-0" width="15%">Unit Price</th>
                          <th class="text-end border-0" width="15%">Amount</th>
                          @if(Auth::user()->canDo(['edit_quotations']))
                          <th class="text-center border-0" width="10%">Actions</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($quotation->details as $row => $detail)
                        <tr class="border-top-8">
                          <td class="text-center align-middle">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">{{$row+1}}</span>
                          </td>
                          <td class="align-middle">
                            <div class="fw-medium">{{ $detail->particular }}</div>
                          </td>
                          <td class="text-center align-middle">
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                              {{ number_format($detail->quantity) }}
                            </span>
                          </td>
                          <td class="text-end align-middle">
                            <div class="d-flex flex-column">
                              <span class="fw-medium">{{ number_format($detail->unit_price, 2) }}</span>
                            </div>
                          </td>
                          <td class="text-end align-middle">
                            <div class="d-flex flex-column">
                              <span class="fw-semibold">{{ number_format($detail->quantity * $detail->unit_price, 2) }}</span>
                            </div>
                          </td>
                          @if(Auth::user()->canDo(['edit_quotations']))
                          <td class="text-center align-middle">
                            <div class="d-flex justify-content-center gap-8">
                              <button type="button" class="btn btn-sm btn-primary btn-icon rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-toggle="modal" data-target="#edit_item{{$detail->id}}" data-bs-toggle="tooltip" title="Edit Item">
                                <i class="ri-pencil-fill"></i>
                              </button>
                              @if(Auth::user()->canDo(['delete_quotations']))
                              <form action="{{ route('quotation_details.destroy', $detail->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-icon rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" data-bs-toggle="tooltip" title="Delete Item">
                                  <i class="ri-delete-bin-2-line"></i>
                                </button>
                              </form>
                              @endif
                            </div>
                          </td>
                          @endif
                        </tr>
                        @include("models.edit_item")
                        @empty
                        <tr>
                          <td colspan="{{ Auth::user()->canDo(['edit_quotations']) ? '6' : '5' }}" class="text-center py-5">
                            <div class="py-5">
                              <div class="avatar avatar-lg bg-light text-primary rounded-circle mb-3 mx-auto">
                                <i class="ri-file-list-3-line fs-3"></i>
                              </div>
                              <h6 class="mb-2">No Items Added Yet</h6>
                              <p class="mb-4 text-muted">This {{ strtolower($quotation->transaction_type) }} doesn't have any items yet.</p>
                              @if(Auth::user()->canDo(['create_quotations']))
                              <button type="button" class="btn btn-primary px-4 py-2" data-toggle="modal" data-target="#add_item">
                                <i class="ri-add-line me-8"></i> Add First Item
                              </button>
                              @endif
                            </div>
                          </td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  @if(count($quotation->details) > 0)
                  <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <i class="ri-list-check-3 text-primary me-8"></i>
                        <span class="text-muted">Total Items: <span class="fw-semibold">{{ count($quotation->details) }}</span></span>
                      </div>
                      <div class="d-flex align-items-center">
                        <i class="ri-money-dollar-circle-line text-primary me-8"></i>
                        <span class="text-muted">Subtotal: <span class="fw-semibold">{{ number_format($quotation->amount($quotation->id), 2) }} {{$quotation->transaction_currency}}</span></span>
                      </div>
                    </div>
                  </div>
                  @endif
                </div>
                <div class="tab-pane fade" id="payments-tab-pane" role="tabpanel" aria-labelledby="payments-tab" tabindex="0">
                  <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                      <thead class="bg-light">
                        <tr>
                          <th class="border-0">Date</th>
                          <th class="border-0">Amount</th>
                          <th class="border-0">Payment Mode</th>
                          <th class="border-0">Recorded By</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($quotation->payments as $payment)
                          <tr>
                            <td>{{ $payment->payment_date->format('d M, Y') }}</td>
                            <td>{{ number_format($payment->amount_paid) }} {{ $quotation->transaction_currency }}</td>
                            <td>{{ $payment->mode_of_payment }}</td>
                            <td>
                              @if($payment->recorder)
                                {{ $payment->recorder->name }}
                              @else
                                <span class="text-muted">N/A</span>
                              @endif
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                              <i class="ri-information-line fs-4 mb-2 d-block"></i>
                              No payments recorded yet.
                            </td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>

            <div class="row g-4">
              <!-- Payment History -->
              <div class="col-md-6">
                <div class="card border h-100">
                  <div class="card-header bg-light py-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center">
                        <i class="ri-history-line text-primary me-8"></i>
                        <h6 class="mb-0">Payment History</h6>
                      </div>
                      @if($quotation->transaction_type=="invoice" && $quotation->balance($quotation->id) > 0)
                      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_payment">
                        <i class="ri-add-line me-8"></i> Record Payment
                      </button>
                      @endif
                    </div>
                  </div>
                  <div class="card-body p-8">
                    @if($quotation->payments->count() > 0)
                    <div class="table-responsive">
                      <table class="table table-hover mb-0">
                        <thead class="table-light">
                          <tr>
                            <th>Date</th>
                            <th class="text-end">Amount</th>
                            <th>Method</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($quotation->payments as $payment)
                          <tr>
                            <td>
                              <div class="d-flex align-items-center">
                                <i class="ri-calendar-line text-muted me-8"></i>
                                <span>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</span>
                              </div>
                            </td>
                            <td class="text-end fw-semibold">
                              <span class="badge bg-success-subtle text-success px-2 py-1">
                                {{ number_format($payment->amount_paid, 2) }} {{$quotation->transaction_currency}}
                              </span>
                            </td>
                            <td>
                              <div class="d-flex align-items-center">
                                <i class="ri-bank-card-line text-muted me-8"></i>
                                <span>{{ $payment->mode_of_payment }}</span>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex align-items-center">
                                <i class="ri-user-line text-muted me-8"></i>
                                <span>
                                  @if($payment->recorder)
                                    {{ $payment->recorder->name }}
                                  @else
                                    <span class="text-muted">N/A</span>
                                  @endif
                                </span>
                              </div>
                            </td>
                            <td class="text-center">
                              <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editPaymentModal{{$payment->id}}">
                                <i class="ri-edit-line"></i>
                              </button>
                            </td>
                          </tr>
                          @include('models.edit_payment', ['payment' => $payment])
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                      <div class="py-4">
                        <i class="ri-bank-card-line d-block mb-3 text-muted" style="font-size: 2.5rem;"></i>
                        <h6 class="mb-2">No Payments Recorded</h6>
                        <p class="mb-3 text-muted">
                          @if($quotation->transaction_type=="invoice")
                          No payments have been recorded for this invoice yet.
                          @else
                          This is a quotation and doesn't require payment.
                          @endif
                        </p>
                        @if($quotation->transaction_type=="invoice")
                        <button type="button" class="btn btn-primary px-4" data-toggle="modal" data-target="#add_payment">
                          <i class="ri-add-line me-8"></i> Record First Payment
                        </button>
                        @endif
                      </div>
                    </div>
                    @endif
                  </div>
                  @if($quotation->payments->count() > 0)
                  <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted">Total Payments: <span class="fw-semibold">{{ $quotation->payments->count() }}</span></span>
                      <span class="text-muted">Total Paid: <span class="fw-semibold text-success">{{ number_format($quotation->paid($quotation->id), 2) }} {{$quotation->transaction_currency}}</span></span>
                    </div>
                  </div>
                  @endif
                </div>
              </div>

              <!-- Financial Summary -->
              <div class="col-md-6">
                <div class="card border h-100">
                  <div class="card-header bg-light py-3">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center">
                        <i class="ri-calculator-line text-primary me-8"></i>
                        <h6 class="mb-0">Financial Summary</h6>
                      </div>
                      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_applicable_taxes">
                        <i class="ri-percent-line me-8"></i> Add Taxes
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">{{ number_format($quotation->amount($quotation->id), 2) }} {{$quotation->transaction_currency}}</span>
                      </div>

                      @foreach($quotation->taxquotations as $taxquotation)
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                          <i class="ri-percent-line text-warning me-8"></i>
                          <span class="fw-medium">{{$taxquotation->tax->tax_name}}</span>
                          @if(Auth::user()->canDo(['delete_taxes']))
                          <form action="{{ route('quotation_taxes.destroy', $taxquotation->id)}}" method="post" onsubmit="return confirm('Delete this tax?');" class="ms-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" style="width: 24px; height: 24px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                              <i class="ri-close-line"></i>
                            </button>
                          </form>
                          @endif
                        </div>
                        <span>{{ number_format($taxquotation->amount($taxquotation->id), 2) }} {{$quotation->transaction_currency}}</span>
                      </div>
                      @endforeach
                    </div>

                    <div class="card bg-primary bg-opacity-10 border-0 mb-4">
                      <div class="card-body p-8">
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-bold text-primary">TOTAL</span>
                          <span class="fw-bold fs-5 text-primary">{{ number_format($quotation->totalAmount($quotation->id), 2) }} {{$quotation->transaction_currency}}</span>
                        </div>
                      </div>
                    </div>

                    @if($quotation->transaction_type=="invoice")
                    <hr>
                    <div class="mt-3">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                          <i class="ri-checkbox-circle-line text-success me-8"></i>
                          <span class="fw-medium">Paid</span>
                        </div>
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                          {{ number_format($quotation->paid($quotation->id), 2) }} {{$quotation->transaction_currency}}
                        </span>
                      </div>

                      <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                          <i class="ri-time-line text-danger me-8"></i>
                          <span class="fw-medium">Balance Due</span>
                        </div>
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                          {{ number_format($quotation->balance($quotation->id), 2) }} {{$quotation->transaction_currency}}
                        </span>
                      </div>

                      @if($quotation->balance($quotation->id) > 0)
                      <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary w-100" data-toggle="modal" data-target="#add_payment">
                          <i class="ri-bank-card-line me-8"></i> Pay Now
                        </button>
                      </div>
                      @endif
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>


            <hr>
            <table class="table table-sm table-striped table-borderless ">
                <tbody>
                    <tr>
                        <td class=""><b>Processed by: </b></td>
                    </tr>

                    <tr>
                        <td><span class="badge p-8">{{$quotation->user->name}}</span></td>
                    </tr>
                </tbody>
            </table>

          </div>
        </div>
      </div>
  </div>
</div>

<!-- Hidden iframe for direct printing will be created dynamically -->

<div class="modal fade" id="printPreviewModal" tabindex="-1" role="dialog" aria-labelledby="printPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printPreviewModalLabel">Print Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-8">
                <iframe id="printPreviewFrame" style="width: 100%; height: 80vh; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printInvoice()">Print</button>
            </div>
        </div>
    </div>
</div>

<div id="add_payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="recordQuotationPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordQuotationPaymentModalLabel">
                    <i class="ri-money-dollar-circle-line me-8 text-success"></i>Record Payment
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-16">
                <form action="{{route('quotation_payments.store')}}" method="post" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="quotation_id" value="{{$quotation->id}}">

                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-danger py-3">
                            <h6 class="mb-0 text-white"><i class="ri-file-list-3-line me-8"></i>Quotation Payment</h6>
                        </div>
                        <div class="card-body p-16">
                            <!-- Quotation Summary -->
                            <div class="alert alert-light border-start border-4 border-success mb-4">
                                <div class="d-flex">
                                    <i class="ri-information-line fs-8 me-8 text-success"></i>
                                    <div>
                                        <p class="mb-1 fw-medium">Quotation #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="mb-0 small text-muted">
                                            Balance Due: <span class="fw-semibold">{{ number_format($quotation->balance($quotation->id)) }} {{$quotation->transaction_currency}}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Date -->
                            <div class="mb-4">
                                <label for="payment_date" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-calendar-line"></i>
                                    <span>Payment Date <span class="text-danger">*</span></span>
                                </label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>

                            <!-- Amount Paid -->
                            <div class="mb-4">
                                <label for="amount_paid" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-money-dollar-circle-line"></i>
                                    <span>Amount Paid ({{$quotation->transaction_currency}}) <span class="text-danger">*</span></span>
                                </label>
                                <input type="number" name="amount_paid" id="amount_paid" value="{{ $quotation->balance($quotation->id) }}" class="form-control" min="0" step="0.01" required>
                                <div class="form-text">Enter the amount received from the customer</div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label for="mode_of_payment" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-bank-card-line"></i>
                                    <span>Payment Method <span class="text-danger">*</span></span>
                                </label>
                                <select name="mode_of_payment" id="mode_of_payment" class="form-select" required>
                                    <option value="">Select payment method</option>
                                    @foreach($mode_of_payments as $mode_of_payment)
                                        <option value="{{ $mode_of_payment }}">{{ $mode_of_payment }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Account to Credit -->
                            <div class="mb-4">
                                <label for="payment_account_id" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-bank-line"></i>
                                    <span>Account to Credit <span class="text-danger">*</span></span>
                                </label>
                                <select name="payment_account_id" id="payment_account_id" class="form-select" required>
                                    <option value="">Select account</option>
                                </select>
                                <div class="form-text">Select the account where the payment will be recorded</div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="narrative" class="form-label fw-semibold label-with-icon">
                                    <i class="ri-text"></i>
                                    <span>Description</span>
                                </label>
                                <input type="text" name="narrative" id="narrative" class="form-control" placeholder="e.g., Payment for Quotation #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}">
                                <div class="form-text">Enter any additional details about this payment</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-end gap-8">
                        <button type="button" class="btn btn-danger px-8" data-dismiss="modal">
                            <i class="ri-close-line me-8"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-8">
                            <i class="ri-save-line me-8"></i> Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include("models.add_quote_item")
@include("models.add_quotation_tax")

<!-- Share Options Modal -->
<div class="modal fade" id="shareOptionsModal" tabindex="-1" role="dialog" aria-labelledby="shareOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareOptionsModalLabel">Share {{ ucfirst($quotation->transaction_type) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6>{{ ucfirst($quotation->transaction_type) }} #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</h6>
                    <p class="text-muted">Choose how you want to share this {{ $quotation->transaction_type }}</p>
                </div>

                <div class="alert alert-dark">
                    <i class="fa fa-dark-circle"></i> The secure PDF link can be shared with anyone. No login required - recipients will get a direct PDF download.
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-link fa-3x mb-3 text-success"></i>
                                <h5>Secure PDF Link</h5>
                                <p class="small">Generate a secure PDF download link</p>
                                <button type="button" onclick="copyShareLink()" class="btn btn-outline-primary btn-block">Copy Link</button>
                                <div id="copyShareMessage" class="small text-success mt-2 d-none">Link copied!</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fa fa-file-pdf-o fa-3x mb-3 text-danger"></i>
                                <h5>Download PDF</h5>
                                <p class="small">Save as PDF document</p>
                                <a href="{{ route('quotations.public.pdf', \App\Helpers\IdHasher::encode($quotation->id)) }}" class="btn btn-outline-danger btn-block">Download</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('styles')
  <style>
    /* Hide elements when printing from the main page */
    @media print {
      #exclude, a, nav, #hide_form, .hp-sidebar, header {
        display: none;
      }
    }

    .p {
      font-weight: bold;
    }

    /* Hidden iframe for printing */
    #hiddenPrintFrame {
      position: fixed;
      right: -9999px;
      bottom: -9999px;
      width: 0;
      height: 0;
      border: 0;
    }

    /* Avatar styles */
    .avatar {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2rem;
      height: 2rem;
      font-size: 1rem;
      border-radius: 50%;
    }

    .avatar-xs {
      width: 1.5rem;
      height: 1.5rem;
      font-size: 0.75rem;
    }

    .avatar-sm {
      width: 2.5rem;
      height: 2.5rem;
      font-size: 1.25rem;
    }

    .avatar-md {
      width: 3rem;
      height: 3rem;
      font-size: 1.5rem;
    }

    .avatar-lg {
      width: 4rem;
      height: 4rem;
      font-size: 2rem;
    }

    /* Custom background colors */
    .bg-success-subtle {
      background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .bg-danger-subtle {
      background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .bg-primary-subtle {
      background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .bg-warning-subtle {
      background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .bg-info-subtle {
      background-color: rgba(13, 202, 240, 0.1) !important;
    }

    /* Hover effects */
    .hover-shadow {
      transition: all 0.3s ease;
    }

    .hover-shadow:hover {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
      transform: translateY(-2px);
    }

    /* Button icon */
    .btn-icon {
      width: 2rem;
      height: 2rem;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    /* Custom table styles */
    .table > :not(caption) > * > * {
      padding: 0.75rem 1rem;
    }

    /* Custom card styles */
    .card {
      border-radius: 0.5rem;
      overflow: hidden;
    }

    .card-header {
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Badge styles */
    .badge {
      font-weight: 500;
    }
  </style>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Initialize tooltips
      $('[data-bs-toggle="tooltip"]').tooltip();

      // Print functionality
      $("#hide_form").click(function(){
        $("#exclude").hide();
        window.print();
      });
    });
  </script>

<script>
    $(document).ready(function() {
        // Initialize chosen selects
        $("#mode_of_payment, #payment_account_id").chosen({
            width: "100%",
            no_results_text: "No results match",
            allow_single_deselect: true
        });

        // Form validation for all forms with needs-validation class
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

        // Handle mode of payment change
        $('#mode_of_payment').on('change', function(e) {
            var mode_of_payment = e.target.value;
            var currency = "{!! $quotation->transaction_currency !!}";

            if (mode_of_payment) {
                $.get('/get_accounts/' + mode_of_payment + '/' + currency, function(data) {
                    $("#payment_account_id").chosen('destroy');
                    $("#payment_account_id").empty();
                    $('#payment_account_id').append("<option value=''>Select account</option>");

                    $.each(data, function(index, account) {
                        $('#payment_account_id').append("<option value=" + account.id + ">" + account.name + " " + account.account_number + " " + account.transaction_currency + "</option>");
                    });

                    $("#payment_account_id").chosen({
                        width: "100%",
                        no_results_text: "No accounts found"
                    });
                });
            }
        });
    });
</script>

<script>
    function showPrintPreview() {
        const modal = new bootstrap.Modal(document.getElementById('printPreviewModal'));
        const iframe = document.getElementById('printPreviewFrame');
        iframe.src = '{{ route("quotations.print", $quotation->id) }}?print=false';
        modal.show();
    }

    function printInvoice() {
        const iframe = document.getElementById('printPreviewFrame');
        if (iframe.contentWindow && iframe.contentWindow.printDocument) {
            iframe.contentWindow.printDocument();
        } else {
            iframe.contentWindow.print();
        }
    }

    // Direct print function
    function printDirectly() {
        // Create a hidden iframe if it doesn't exist
        let printFrame = document.getElementById('hiddenPrintFrame');
        if (!printFrame) {
            printFrame = document.createElement('iframe');
            printFrame.id = 'hiddenPrintFrame';
            printFrame.name = 'hiddenPrintFrame';
            printFrame.style.position = 'fixed';
            printFrame.style.right = '-9999px';
            printFrame.style.bottom = '-9999px';
            printFrame.style.width = '0';
            printFrame.style.height = '0';
            printFrame.style.border = '0';
            document.body.appendChild(printFrame);
        }

        // Load the print page into the iframe
        printFrame.src = '{{ route("quotations.print", $quotation->id) }}?print=true';

        // Wait for the iframe to load, then print it
        printFrame.onload = function() {
            setTimeout(function() {
                try {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                } catch (e) {
                    console.error('Printing failed:', e);
                    // Fallback to opening in a new tab
                    window.open('{{ route("quotations.print", $quotation->id) }}?print=true', '_blank');
                }
            }, 500);
        };
    }

    // Update your print buttons to use these functions
    document.querySelector('.print-button').onclick = showPrintPreview;
    document.querySelector('.direct-print-button').onclick = printDirectly;

    function copyShareLink() {
        // Use IdHasher directly to ensure consistent encryption
        const secureUrl = '{{ route("quotations.public.pdf", \App\Helpers\IdHasher::encode($quotation->id)) }}';

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

    function fallbackCopyTextToClipboard(text) {
        // Create a temporary input element
        const tempInput = document.createElement('input');
        // Set its value to the secure URL
        tempInput.value = text;
        // Add it to the document
        document.body.appendChild(tempInput);
        // Select its contents
        tempInput.select();
        // Copy the selected text
        document.execCommand('copy');
        // Remove the temporary element
        document.body.removeChild(tempInput);

        // Show success message
        const copyMessage = document.getElementById("copyShareMessage");
        copyMessage.classList.remove("d-none");

        // Hide message after 3 seconds
        setTimeout(function() {
            copyMessage.classList.add("d-none");
        }, 3000);
    }

    function copyInputLink() {
        // Get the input field
        const copyText = document.getElementById("securePublicLink");
        const btn = document.getElementById("copyButton");
        const originalText = btn.textContent;

        // Try to use the modern Clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(copyText.value)
                .then(() => {
                    // Show a temporary tooltip
                    btn.textContent = "Copied!";

                    // Reset button text after 2 seconds
                    setTimeout(function() {
                        btn.textContent = originalText;
                    }, 2000);
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                    // Fallback to the old method
                    fallbackCopyInputToClipboard(copyText, btn, originalText);
                });
        } else {
            // Fallback for browsers that don't support the Clipboard API
            fallbackCopyInputToClipboard(copyText, btn, originalText);
        }
    }

    function fallbackCopyInputToClipboard(copyText, btn, originalText) {
        // Select the text
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        // Copy the text
        document.execCommand("copy");

        // Show a temporary tooltip
        btn.textContent = "Copied!";

        // Reset button text after 2 seconds
        setTimeout(function() {
            btn.textContent = originalText;
        }, 2000);
    }
</script>
@endpush