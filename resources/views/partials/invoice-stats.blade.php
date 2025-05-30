<!-- Invoice Stats Cards -->
<div class="row g-3 mb-4">
    <!-- Pending Invoices Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <a href="{{ route('invoices.index', ['filter' => 'pending']) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px; background-color: rgba(255, 193, 7, 0.1);">
                            <i class="ri-time-line fs-4 text-warning"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-dark">Pending</h5>
                                <div>
                                    <span class="badge bg-warning text-dark me-1">{{ $totalPendingUGX['count'] ?? 0 }} UGX</span>
                                    <span class="badge bg-warning text-dark">{{ $totalPendingUSD['count'] ?? 0 }} USD</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted small">Amount:</span>
                                    <div class="text-end">
                                        <span class="d-block">
                                            <i class="ri-money-dollar-circle-line text-primary me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalPendingUGX['amount'] ?? 0) }}</span> UGX
                                        </span>
                                        <span class="d-block mt-1">
                                            <i class="ri-money-dollar-circle-line text-success me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalPendingUSD['amount'] ?? 0) }}</span> USD
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Paid Invoices Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <a href="{{ route('invoices.index', ['filter' => 'paid']) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px; background-color: rgba(25, 135, 84, 0.1);">
                            <i class="ri-checkbox-circle-line fs-4 text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-dark">Paid</h5>
                                <div>
                                    <span class="badge bg-success text-white me-1">{{ $totalPaidUGX['count'] ?? 0 }} UGX</span>
                                    <span class="badge bg-success text-white">{{ $totalPaidUSD['count'] ?? 0 }} USD</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted small">Amount:</span>
                                    <div class="text-end">
                                        <span class="d-block">
                                            <i class="ri-money-dollar-circle-line text-primary me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalPaidUGX['amount'] ?? 0) }}</span> UGX
                                        </span>
                                        <span class="d-block mt-1">
                                            <i class="ri-money-dollar-circle-line text-success me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalPaidUSD['amount'] ?? 0) }}</span> USD
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Partially Paid Invoices Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <a href="{{ route('invoices.index', ['filter' => 'partially_paid']) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px; background-color: rgba(13, 110, 253, 0.1);">
                            <i class="ri-money-dollar-circle-line fs-4 text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-dark">Partially Paid</h5>
                                <div>
                                    <span class="badge bg-info text-white me-1">{{ $totalPartiallyPaidUGX['count'] ?? 0 }} UGX</span>
                                    <span class="badge bg-info text-white">{{ $totalPartiallyPaidUSD['count'] ?? 0 }} USD</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted small">Remaining:</span>
                                    <div class="text-end">
                                        <span class="d-block">
                                            <i class="ri-money-dollar-circle-line text-primary me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalPartiallyPaidUGX['amount'] ?? 0) }}</span> UGX
                                        </span>
                                        <span class="d-block mt-1">
                                            <i class="ri-money-dollar-circle-line text-success me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalPartiallyPaidUSD['amount'] ?? 0) }}</span> USD
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Overdue Invoices Card -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-3">
                <a href="{{ route('invoices.index', ['filter' => 'overdue']) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px; background-color: rgba(220, 53, 69, 0.1);">
                            <i class="ri-error-warning-line fs-4 text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0 text-dark">Overdue</h5>
                                <div>
                                    <span class="badge bg-danger text-white me-1">{{ $totalOverdueUGX['count'] ?? 0 }} UGX</span>
                                    <span class="badge bg-danger text-white">{{ $totalOverdueUSD['count'] ?? 0 }} USD</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text-muted small">Outstanding:</span>
                                    <div class="text-end">
                                        <span class="d-block">
                                            <i class="ri-money-dollar-circle-line text-primary me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalOverdueUGX['amount'] ?? 0) }}</span> UGX
                                        </span>
                                        <span class="d-block mt-1">
                                            <i class="ri-money-dollar-circle-line text-success me-1"></i>
                                            <span class="text-dark fw-medium">{{ number_format($totalOverdueUSD['amount'] ?? 0) }}</span> USD
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
