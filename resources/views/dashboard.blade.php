@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Dashboard</h4>
                    <p class="text-sm text-secondary mb-0">Welcome back, {{ Auth::user()->name }}. Here's what's happening today.</p>
                </div>
                <div>
                    <a href="{{ route('fuel-requests.create') }}" class="btn btn-primary btn-sm">
                        <i class="material-symbols-rounded me-2">add</i>New Request
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistic Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Clients</p>
                                <h5 class="font-weight-bolder mb-0">{{ $activeClients ?? 142 }}</h5>
                                <p class="mb-0 text-success text-sm">
                                    <span class="text-success font-weight-bolder">+12%</span> from last month
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="material-symbols-rounded text-lg opacity-10">people</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Requests</p>
                                <h5 class="font-weight-bolder mb-0">{{ $pendingApprovals ?? 28 }}</h5>
                                <p class="mb-0 text-success text-sm">
                                    <span class="text-success font-weight-bolder">+5</span> from last month
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="material-symbols-rounded text-lg opacity-10">schedule</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Monthly Revenue</p>
                                <h5 class="font-weight-bolder mb-0">TZS {{ number_format($totalRevenue ?? 89240000) }}</h5>
                                <p class="mb-0 text-success text-sm">
                                    <span class="text-success font-weight-bolder">+18%</span> from last month
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="material-symbols-rounded text-lg opacity-10">attach_money</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Credit Alerts</p>
                                <h5 class="font-weight-bolder mb-0">{{ $creditAlerts ?? 7 }}</h5>
                                <p class="mb-0 text-danger text-sm">
                                    <span class="text-danger font-weight-bolder">-2</span> from last month
                                </p>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                <i class="material-symbols-rounded text-lg opacity-10">warning</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="row">
        <!-- Recent Fuel Requests -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <i class="material-symbols-rounded text-primary me-2">schedule</i>
                        <h6 class="mb-0">Recent Fuel Requests</h6>
                    </div>
                    <p class="text-sm text-secondary mb-0">Latest fuel requests requiring attention</p>
                </div>
                <div class="card-body p-3">
                    <!-- REQ-001 -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-sm bg-gradient-danger rounded-circle">
                                <span class="text-white text-xs font-weight-bold">REQ-001</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 text-sm font-weight-bold">ABC Transport Ltd</h6>
                                    <p class="text-xs text-secondary mb-0">TRK-4521 • 500L</p>
                                    <p class="text-xs text-secondary mb-0">
                                        <i class="material-symbols-rounded text-xs me-1">location_on</i>Station Alpha
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- REQ-002 -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-sm bg-gradient-secondary rounded-circle">
                                <span class="text-white text-xs font-weight-bold">REQ-002</span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 text-sm font-weight-bold">Metro Logistics</h6>
                                    <p class="text-xs text-secondary mb-0">VAN-8934 • 200L</p>
                                    <p class="text-xs text-secondary mb-0">
                                        <i class="material-symbols-rounded text-xs me-1">location_on</i>Station Beta
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge badge-sm bg-gradient-success">Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Utilization -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex align-items-center">
                        <i class="material-symbols-rounded text-primary me-2">show_chart</i>
                        <h6 class="mb-0">Credit Utilization</h6>
                    </div>
                    <p class="text-sm text-secondary mb-0">Client credit limit usage</p>
                </div>
                <div class="card-body p-3">
                    <!-- ABC Transport Ltd -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 text-sm font-weight-bold">ABC Transport Ltd</h6>
                            <span class="text-xs text-secondary">75% utilized</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs text-secondary">TZS 75M / TZS 100M</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-warning" style="width: 75%"></div>
                        </div>
                    </div>
                    
                    <!-- Metro Logistics -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 text-sm font-weight-bold">Metro Logistics</h6>
                            <span class="text-xs text-secondary">75% utilized</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs text-secondary">TZS 45M / TZS 60M</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-warning" style="width: 75%"></div>
                        </div>
                    </div>
                    
                    <!-- City Delivery Co -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 text-sm font-weight-bold">City Delivery Co</h6>
                            <span class="text-xs text-secondary">90% utilized</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-xs text-secondary">TZS 90M / TZS 100M</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient-danger" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
