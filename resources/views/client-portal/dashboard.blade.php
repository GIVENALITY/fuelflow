@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Clean Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Dashboard</h2>
                        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }} • {{ $client->company_name }}</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Quick Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('client-portal.requests.create') }}">New Fuel Request</a></li>
                            <li><a class="dropdown-item" href="{{ route('client-portal.vehicles.index') }}">Manage Vehicles</a></li>
                            <li><a class="dropdown-item" href="{{ route('client-portal.reports.index') }}">View Reports</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Stats Cards - Like the Reference Dashboard -->
        <div class="row mb-5">
            <!-- Available Credit -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-shape bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-credit-card text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 font-weight-bold">TZS {{ number_format($client->available_credit, 0) }}</h3>
                        <p class="text-muted mb-0 small">Available Credit</p>
                    </div>
                </div>
            </div>

            <!-- Active Vehicles -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-shape bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-truck text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 font-weight-bold">{{ $activeVehicles }}</h3>
                        <p class="text-muted mb-0 small">Active Vehicles</p>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-shape bg-warning rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-clock text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 font-weight-bold">{{ $pendingRequests }}</h3>
                        <p class="text-muted mb-0 small">Pending Requests</p>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-shape bg-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-line text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 font-weight-bold">{{ $monthlyRequests }}</h3>
                        <p class="text-muted mb-0 small">This Month</p>
                    </div>
                </div>
            </div>

            <!-- Total Spent -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-shape bg-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-gas-pump text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 font-weight-bold">TZS {{ number_format($client->current_balance, 0) }}</h3>
                        <p class="text-muted mb-0 small">Total Spent</p>
                    </div>
                </div>
            </div>

            <!-- Credit Limit -->
            <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <div class="icon-shape bg-dark rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-chart-pie text-white" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <h3 class="mb-1 font-weight-bold">TZS {{ number_format($client->credit_limit, 0) }}</h3>
                        <p class="text-muted mb-0 small">Credit Limit</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Bottom Section -->
        <div class="row">
            <!-- Recent Requests - Clean Table -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">Recent Fuel Requests</h5>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('client-portal.requests.index') }}">View All</a></li>
                                    <li><a class="dropdown-item" href="{{ route('client-portal.requests.create') }}">New Request</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recentRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-bold">Request</th>
                                            <th class="border-0 fw-bold">Vehicle</th>
                                            <th class="border-0 fw-bold text-center">Status</th>
                                            <th class="border-0 fw-bold text-center">Amount</th>
                                            <th class="border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentRequests as $request)
                                            <tr>
                                                <td class="py-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">#{{ $request->id }}</h6>
                                                        <small class="text-muted">{{ $request->created_at->format('M d, Y') }}</small>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $request->vehicle->plate_number }}</h6>
                                                        <small class="text-muted">{{ $request->vehicle->make }} {{ $request->vehicle->model }}</small>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <span class="badge {{ $request->status_badge }} px-3 py-2">{{ $request->status_display_name }}</span>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <h6 class="mb-0 fw-bold">TZS {{ number_format($request->total_amount, 0) }}</h6>
                                                </td>
                                                <td class="py-3">
                                                    <a href="{{ route('client-portal.requests.show', $request) }}" class="btn btn-outline-primary btn-sm">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-gas-pump text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted mb-3">No recent requests</h5>
                                <p class="text-muted mb-4">Submit your first fuel request to get started.</p>
                                <a href="{{ route('client-portal.requests.create') }}" class="btn btn-primary px-4">
                                    <i class="fas fa-plus me-2"></i>New Request
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Overview - Clean Stats -->
            <div class="col-lg-4">
                <!-- Credit Overview -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="mb-0 font-weight-bold">Credit Overview</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Utilization</span>
                                <span class="fw-bold">{{ number_format($client->credit_utilization, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $client->credit_utilization > 80 ? 'bg-danger' : ($client->credit_utilization > 60 ? 'bg-warning' : 'bg-success') }}"
                                    style="width: {{ $client->credit_utilization }}%"></div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Credit Limit:</span>
                                <span class="fw-bold">TZS {{ number_format($client->credit_limit, 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Current Balance:</span>
                                <span class="fw-bold">TZS {{ number_format($client->current_balance, 0) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Available Credit:</span>
                                <span class="fw-bold text-success">TZS {{ number_format($client->available_credit, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4">
                        <h5 class="mb-0 font-weight-bold">Account Status</h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <span class="badge {{ $client->status_badge }} px-4 py-3 fs-6">{{ $client->status_display_name }}</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('client-portal.profile') }}" class="btn btn-outline-primary">
                                <i class="fas fa-cog me-2"></i>Account Settings
                            </a>
                            <a href="{{ route('client-portal.payments.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-credit-card me-2"></i>Make Payment
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection