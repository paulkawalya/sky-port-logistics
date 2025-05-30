@extends('layouts.app')

@include('layouts.data_tables')

@section('styles')
<style>
    /* Dashboard card styling */
    .dashboard-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .dashboard-card .card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #eaeaea;
    }

    .dashboard-card .card-title {
        margin-bottom: 0;
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .dashboard-card .card-title i {
        margin-right: 10px;
        color: #0063f7;
    }

    /* Status card styling */
    .status-card {
        padding: 16px;
        border-radius: 10px;
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .status-card:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom right, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        pointer-events: none;
    }

    .active-status-card {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        border-width: 2px !important;
    }

    /* Welcome card styling */
    .welcome-card {
        overflow: hidden;
        transition: all 0.3s;
    }

    .welcome-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 99, 247, 0.15);
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }

    .welcome-icon {
        animation: float 3s ease-in-out infinite;
    }

    /* Dashboard stat cards styling */
    .dashboard-stat-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        height: 100%;
        position: relative;
        transition: all 0.3s ease;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
    }

    .stat-card-header {
        padding: 20px 25px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card-title {
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 16px;
        color: #333;
    }

    .stat-card-title i {
        font-size: 20px;
        margin-right: 10px;
    }

    .stat-card-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 99, 247, 0.1);
        color: #0063f7;
        transition: all 0.3s;
    }

    .stat-card-action:hover {
        background: #0063f7;
        color: white;
        transform: translateX(3px);
    }

    .stat-card-body {
        padding: 20px 25px;
    }

    .stat-card-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 12px;
        background: #f8f9fa;
        transition: all 0.3s;
        cursor: pointer;
    }

    .stat-card-item:last-child {
        margin-bottom: 0;
    }

    .stat-card-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-card-value {
        display: flex;
        flex-direction: column;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-card-icon i {
        font-size: 22px;
    }



    /* Minimalist Stats Styling */
    .stats-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 20px 15px;
        text-align: center;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.06);
    }

    .stat-icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
    }

    .stat-icon-circle i {
        font-size: 22px;
        color: white;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0 0 5px;
    }

    .stat-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }

    /* Soft background colors */
    .blue-bg {
        background-color: #e6f3ff;
    }

    .blue-bg i {
        color: #0063f7;
    }

    .green-bg {
        background-color: #e6f7ee;
    }

    .green-bg i {
        color: #28a745;
    }

    .yellow-bg {
        background-color: #fff8e6;
    }

    .yellow-bg i {
        color: #ffc107;
    }

    .orange-bg {
        background-color: #fff0e6;
    }

    .orange-bg i {
        color: #fd7e14;
    }

    .teal-bg {
        background-color: #e6f7f9;
    }

    .teal-bg i {
        color: #17a2b8;
    }

    .purple-bg {
        background-color: #eee6ff;
    }

    .purple-bg i {
        color: #6f42c1;
    }

    /* Active filter state */
    .payment-filter.active-filter {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }

    .payment-filter[data-payment-filter="paid"].active-filter {
        color: #28a745;
    }

    .payment-filter[data-payment-filter="not_paid"].active-filter {
        color: #ffc107;
    }






</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card welcome-card">
                <div class="card-body p-0">
                    <div style="background: linear-gradient(to right, #0063f7, #0052cc); border-radius: 8px; padding: 24px; position: relative; overflow: hidden;">
                        <div style="position: absolute; top: 0; right: 0; width: 300px; height: 100%; background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxkZWZzPgogICAgICAgIDxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB4PSIwIiB5PSIwIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSgzMCkiPgogICAgICAgICAgICA8cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMTAiIGhlaWdodD0iMTAiIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPgogICAgICAgIDwvcGF0dGVybj4KICAgIDwvZGVmcz4KICAgIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjcGF0dGVybikiLz4KPC9zdmc+'); background-size: cover; opacity: 0.2;"></div>
                        <div class="d-flex align-items-center position-relative">
                            <div class="flex-shrink-0 me-8">
                                <div style="width: 64px; height: 64px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); position: relative; overflow: hidden;">
                                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);"></div>
                                    <i class="ri-ship-line welcome-icon" style="font-size: 30px; color: white; position: relative; display: inline-block;"></i>
                                </div>
                            </div>
                            <div>
                                <h3 style="margin-bottom: 8px; color: white; font-weight: 600;">Welcome back, {{ Auth::user()->name }}</h3>
                                <p style="margin-bottom: 0; color: rgba(255, 255, 255, 0.8); font-size: 16px;">Here's what's happening with your shipments today.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="ri-ship-line"></i> Container Status</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="status-card" data-filter="All" style="border-radius: 10px; border: 1px solid rgba(0, 99, 247, 0.1); background-color: white; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(0, 99, 247, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                    <i class="ri-apps-line" style="font-size: 24px; color: #0063f7;"></i>
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px; color: #333;">{{ count($transits) }}</h3>
                                <p style="font-size: 14px; color: #0063f7; font-weight: 500; margin-bottom: 0;">All</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="status-card active-status-card" data-filter="In Transit" style="border-radius: 10px; border: 1px solid rgba(23, 162, 184, 0.1); background-color: white; padding: 15px; text-align: center; box-shadow: 0 8px 15px rgba(0,0,0,0.1); transition: all 0.3s; transform: translateY(-5px);">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(23, 162, 184, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                    <i class="ri-route-line" style="font-size: 24px; color: #17a2b8;"></i>
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $in_transit }}</h3>
                                <p style="font-size: 14px; color: #17a2b8; font-weight: 500; margin-bottom: 0;">In Transit</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="status-card" data-filter="On Port" style="border-radius: 10px; border: 1px solid rgba(255, 193, 7, 0.1); background-color: white; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(255, 193, 7, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                    <i class="ri-anchor-line" style="font-size: 24px; color: #ffc107;"></i>
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $on_port }}</h3>
                                <p style="font-size: 14px; color: #ffc107; font-weight: 500; margin-bottom: 0;">On Port</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="status-card" data-filter="Pickedup" style="border-radius: 10px; border: 1px solid rgba(40, 167, 69, 0.1); background-color: white; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(40, 167, 69, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                    <i class="ri-truck-line" style="font-size: 24px; color: #28a745;"></i>
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $pickedup }}</h3>
                                <p style="font-size: 14px; color: #28a745; font-weight: 500; margin-bottom: 0;">Picked Up</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="status-card" data-filter="In Warehouse" style="border-radius: 10px; border: 1px solid rgba(108, 117, 125, 0.1); background-color: white; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(108, 117, 125, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                    <i class="ri-store-2-line" style="font-size: 24px; color: #6c757d;"></i>
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $in_warehouse }}</h3>
                                <p style="font-size: 14px; color: #6c757d; font-weight: 500; margin-bottom: 0;">In Warehouse</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="status-card" data-filter="Transferred" style="border-radius: 10px; border: 1px solid rgba(220, 53, 69, 0.1); background-color: white; padding: 15px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s;">
                                <div style="width: 50px; height: 50px; border-radius: 50%; background-color: rgba(220, 53, 69, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                    <i class="ri-exchange-box-line" style="font-size: 24px; color: #dc3545;"></i>
                                </div>
                                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 5px; color: #333;">{{ $transferred }}</h3>
                                <p style="font-size: 14px; color: #dc3545; font-weight: 500; margin-bottom: 0;">Transferred</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row mb-16">
        <!-- Invoice Overview -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="mb-0">Invoice Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 50px; height: 50px;">
                                    <i class="ri-checkbox-circle-line fs-4"></i>
                                </div>
                                <h3 class="fs-4 fw-bold mb-1" id="paid_invoices">...</h3>
                                <p class="text-muted small mb-0">Paid Invoices</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 50px; height: 50px;">
                                    <i class="ri-time-line fs-4"></i>
                                </div>
                                <h3 class="fs-4 fw-bold mb-1" id="pending_invoices">...</h3>
                                <p class="text-muted small mb-0">Pending Invoices</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="mb-0">Payment Status</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center payment-filter" data-payment-filter="paid">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle mb-3" style="width: 50px; height: 50px;">
                                    <i class="ri-checkbox-circle-line fs-4"></i>
                                </div>
                                <h3 class="fs-4 fw-bold mb-1" id="paid_containers">...</h3>
                                <p class="text-muted small mb-0">Paid Containers</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center payment-filter" data-payment-filter="not_paid">
                                <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle mb-3" style="width: 50px; height: 50px;">
                                    <i class="ri-time-line fs-4"></i>
                                </div>
                                <h3 class="fs-4 fw-bold mb-1" id="not_paid_containers">...</h3>
                                <p class="text-muted small mb-0">Not Paid</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- People Stats -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <h5 class="mb-0">People</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle mb-3" style="width: 50px; height: 50px;">
                                    <i class="ri-ship-line fs-4"></i>
                                </div>
                                <h3 class="fs-4 fw-bold mb-1">{{$agents}}</h3>
                                <p class="text-muted small mb-0">Shippers</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 50px; height: 50px;">
                                    <i class="ri-group-line fs-4"></i>
                                </div>
                                <h3 class="fs-4 fw-bold mb-1">{{$customers}}</h3>
                                <p class="text-muted small mb-0">Clients</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Containers Table -->
    <div class="dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title"><i class="ri-inbox-archive-line"></i> Recent Containers</h5>
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center me-3 d-none" id="table-loading-indicator">
                    <div class="spinner-grow spinner-grow-sm text-primary me-2" role="status" style="width: 0.5rem; height: 0.5rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted small">Loading data...</span>
                </div>
                <a href="{{ route('containers.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ri-eye-line me-8"></i> View All
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table table-hover" id="tabledata" width="100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Container</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transits as $transit)
                        @php
                            $statusInfo = [
                                'In Transit' => ['#17a2b8', 'rgba(23, 162, 184, 0.1)', 'ri-route-line'],
                                'On Port' => ['#ffc107', 'rgba(255, 193, 7, 0.1)', 'ri-anchor-line'],
                                'Pickedup' => ['#28a745', 'rgba(40, 167, 69, 0.1)', 'ri-truck-line'],
                                'In Warehouse' => ['#6c757d', 'rgba(108, 117, 125, 0.1)', 'ri-store-2-line'],
                                'Transferred' => ['#dc3545', 'rgba(220, 53, 69, 0.1)', 'ri-exchange-box-line']
                            ][$transit->status($transit->id)] ?? ['#0063f7', 'rgba(0, 99, 247, 0.1)', 'ri-question-line'];
                        @endphp
                        <tr data-status="{{ $transit->status($transit->id) }}">
                            <td data-order="{{ $transit->created_at->timestamp }}">
                                <span>{{ $transit->created_at->format('M d, Y') }}</span>
                                <span class="d-none">{{ $transit->created_at->timestamp }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width: 32px; height: 32px; border-radius: 6px; background-color: rgba(0, 99, 247, 0.1); display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                                        <i class="ri-ship-line" style="color: #0063f7; font-size: 16px;"></i>
                                    </div>
                                    <a href="{{ route('containers.details', $transit->id) }}" class="fw-bold text-decoration-none">{{ $transit->number }}</a>
                                </div>
                            </td>
                            <td>{{ $transit->entry_number }}</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $transit->category }}</span>
                            </td>
                            <td>
                                <div style="display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 20px; background-color: {{ $statusInfo[1] }}; color: {{ $statusInfo[0] }}; font-weight: 500;">
                                    <i class="{{ $statusInfo[2] }} me-8"></i>
                                    {{ $transit->status($transit->id) }}
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex">
                                    @if(Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['view_shipment']))
                                    <a href="{{ route('containers.show', $transit->id) }}" class="btn btn-sm" style="background-color: rgba(0, 99, 247, 0.1); color: #0063f7; border: none; margin-right: 8px; border-radius: 6px;">
                                        <i class="ri-eye-line me-8"></i> View
                                    </a>
                                    @endif

                                    @if(Auth::check() && method_exists(Auth::user(), 'canDo') && Auth::user()->canDo(['edit_containers']))
                                    <a href="{{ route('containers.edit', $transit->id) }}" class="btn btn-sm" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d; border: none; border-radius: 6px;">
                                        <i class="ri-edit-line me-8"></i> Edit
                                    </a>
                                    @endif
                                </div>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Performance optimization: Use Promise.all for parallel API requests
        const loadStats = () => {
            // Show loading indicators
            const statsElements = $("#paid_invoices, #pending_invoices, #paid_containers, #not_paid_containers");
            const spinner = '<span style="width: 20px; height: 20px; border: 2px solid #ccc; border-top-color: #333; border-radius: 50%; display: inline-block; animation: spin 1s linear infinite;"></span>';
            statsElements.html(spinner);
            
            // Set a timeout to ensure spinners don't load forever
            const statsTimeout = setTimeout(() => {
                statsElements.text('N/A');
                console.warn('Stats loading timed out');
            }, 5000);
            
            // Create promises for all API calls
            const invoicePromise = fetch("{{ url('return_number') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json());
            
            const paymentPromise = fetch("{{ url('payment_status_count') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json());
            
            // Execute all promises in parallel
            Promise.all([invoicePromise, paymentPromise])
                .then(([invoiceData, paymentData]) => {
                    // Clear the timeout since we got a response
                    clearTimeout(statsTimeout);
                    
                    // Update invoice statistics
                    $("#paid_invoices").text(invoiceData.paid_invoices || 0);
                    $("#pending_invoices").text(invoiceData.pending_invoices || 0);
                    
                    // Update payment statistics
                    $("#paid_containers").text(paymentData.paid || 0);
                    $("#not_paid_containers").text(paymentData.not_paid || 0);
                })
                .catch(error => {
                    // Clear the timeout since we got an error
                    clearTimeout(statsTimeout);
                    console.error('Error loading statistics:', error);
                    $("#paid_invoices, #pending_invoices, #paid_containers, #not_paid_containers").text('N/A');
                });
        };
        
        // Load statistics immediately
        loadStats();
        
        // Show loading indicator for table
        $('#table-loading-indicator').removeClass('d-none');
        
        // Set a timeout to hide the loading indicator if it gets stuck
        const tableLoadingTimeout = setTimeout(() => {
            $('#table-loading-indicator').addClass('d-none');
            console.warn('Table loading timed out after 10 seconds');
        }, 10000);
        
        // Initialize DataTable with simplified configuration
        var dataTable = $('#tabledata').DataTable({
            "processing": true,
            "serverSide": false, // Client-side processing for better performance
            "ajax": {
                "url": "{{ url('containers') }}", // Use regular containers endpoint
                "dataSrc": function(json) {
                    // Clear the timeout since we got a response
                    clearTimeout(tableLoadingTimeout);
                    // Hide loading indicator in header
                    $('#table-loading-indicator').addClass('d-none');
                    
                    // Log the received data for debugging
                    console.log('Received data:', json);
                    
                    return json;
                },
                "error": function(xhr, error, thrown) {
                    // Clear the timeout since we got an error
                    clearTimeout(tableLoadingTimeout);
                    // Handle and log errors
                    console.error('DataTables Ajax error:', error, thrown);
                    console.log('Server response:', xhr.responseText);
                    $('#table-loading-indicator').addClass('d-none');
                    
                    // Show a more user-friendly error message
                    const tableContainer = document.querySelector('.table-container');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger mt-3';
                    errorDiv.innerHTML = '<strong>Error:</strong> Could not load container data. Please refresh the page.';
                    tableContainer.prepend(errorDiv);
                }
            },
            "pageLength": 10,
            "order": [[0, 'desc']],
            "columns": [
                { 
                    "data": "date_arrived",
                    "defaultContent": "-"
                },
                { 
                    "data": "container_number",
                    "defaultContent": "N/A"
                },
                { 
                    "data": "customer",
                    "defaultContent": "N/A"
                },
                { 
                    "data": "status",
                    "defaultContent": "<div class='badge bg-secondary rounded-pill'>Unknown</div>"
                },
                { 
                    "data": "payment",
                    "defaultContent": "<div class='badge bg-secondary rounded-pill'>Unknown</div>"
                },
                { 
                    "data": "actions",
                    "defaultContent": ""
                }
            ],
            "dom": '<"top"f>rt<"bottom"lip><"clear">',  // Optimize DOM structure
            "language": {
                "processing": '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                "zeroRecords": "No matching containers found",
                "info": "Showing _START_ to _END_ of _TOTAL_ containers",
                "infoEmpty": "No containers available",
                "infoFiltered": "(filtered from _MAX_ total containers)"
            },
            "drawCallback": function() {
                // Hide loading indicator when data is drawn
                $('#table-loading-indicator').addClass('d-none');
            },
            "initComplete": function() {
                // Set default filter to "In Transit" after initialization
                this.api().search('In Transit').draw();
            }
        });

        // Filter by container status when clicking on status cards
        $('.status-card[data-filter]').on('click', function() {
            var status = $(this).data('filter');

            // Reset all container status cards
            $('.status-card[data-filter]').removeClass('active-status-card').css({
                'transform': 'translateY(0)',
                'box-shadow': '0 2px 8px rgba(0,0,0,0.05)'
            });

            // Highlight the selected status card
            $(this).addClass('active-status-card').css({
                'transform': 'translateY(-5px)',
                'box-shadow': '0 8px 15px rgba(0,0,0,0.1)'
            });

            if (status === 'All') {
                dataTable.search('').columns().search('').draw();
            } else {
                dataTable.search(status).draw();
            }
        });

        // Filter by payment status when clicking on payment status cards
        $('.payment-filter[data-payment-filter]').on('click', function() {
            var paymentStatus = $(this).data('payment-filter');

            // Reset all payment status cards
            $('.payment-filter[data-payment-filter]').removeClass('active-filter');

            // Highlight the selected payment status card
            $(this).addClass('active-filter');

            // Apply the payment status filter
            if (paymentStatus === 'all') {
                // Clear payment status filter
                dataTable.column(5).search('').draw();
            } else {
                // Apply payment status filter
                dataTable.column(5).search(paymentStatus).draw();
            }
        });

        // Add hover effects to dashboard cards
        $('.dashboard-card').hover(
            function() { $(this).css('transform', 'translateY(-5px)'); },
            function() { $(this).css('transform', 'translateY(0)'); }
        );

        // Add keyframe animation for spinner
        $('<style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>').appendTo('head');

        // Apply status badge class to status indicators
        $('#tabledata tbody tr').each(function() {
            $(this).find('td:nth-child(5) div').addClass('status-badge');

            // Apply action button classes
            $(this).find('td:last-child a:contains("View")').addClass('action-btn view-btn');
            $(this).find('td:last-child a:contains("Edit")').addClass('action-btn edit-btn');
        });
    });
</script>
@endpush
