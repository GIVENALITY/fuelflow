@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2">Welcome back, {{ auth()->user()->name }}!</h4>
                                <p class="mb-0 opacity-8">
                                    Manage your fuel requests, track your fleet, and monitor your account status.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="text-center">
                                    <h3 class="mb-0">{{ $client->company_name }}</h3>
                                    <p class="mb-0 opacity-8">Corporate Account</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Available Credit</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        ${{ number_format($client->available_credit, 2) }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Vehicles</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $activeVehicles }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingRequests }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="ni ni-time-alarm text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This Month</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $monthlyRequests }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Requests -->
            <div class="col-lg-8 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">Recent Fuel Requests</h6>
                            <a href="{{ route('client-portal.requests') }}" class="btn btn-outline-primary btn-sm">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if($recentRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Request</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Vehicle</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Amount</th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentRequests as $request)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">#{{ $request->id }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $request->created_at->format('M d, Y') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-sm">{{ $request->vehicle->plate_number }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $request->vehicle->make }}
                                                            {{ $request->vehicle->model }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span
                                                        class="badge badge-sm {{ $request->status_badge }}">{{ $request->status_display_name }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">${{ number_format($request->total_amount, 2) }}</span>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('client-portal.requests.show', $request) }}"
                                                        class="text-secondary font-weight-bold text-xs">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ni ni-delivery-fast text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">No recent requests</h6>
                                <p class="text-muted">Submit your first fuel request to get started.</p>
                                <a href="{{ route('client-portal.requests.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    New Request
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Account Info -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Quick Actions</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('client-portal.requests.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                New Fuel Request
                            </a>
                            <a href="{{ route('client-portal.vehicles') }}" class="btn btn-outline-primary">
                                <i class="fas fa-car me-2"></i>
                                Manage Vehicles
                            </a>
                            <a href="{{ route('client-portal.reports') }}" class="btn btn-outline-primary">
                                <i class="fas fa-chart-bar me-2"></i>
                                View Reports
                            </a>
                            <a href="{{ route('client-portal.profile') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>
                                Account Settings
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Account Status</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-sm font-weight-bold">Credit Utilization</span>
                                <span class="text-sm">{{ number_format($client->credit_utilization, 1) }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar {{ $client->credit_utilization > 80 ? 'bg-danger' : ($client->credit_utilization > 60 ? 'bg-warning' : 'bg-success') }}"
                                    style="width: {{ $client->credit_utilization }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Credit Limit:</span>
                                <span class="text-sm font-weight-bold">${{ number_format($client->credit_limit, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Current Balance:</span>
                                <span
                                    class="text-sm font-weight-bold">${{ number_format($client->current_balance, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-sm">Available Credit:</span>
                                <span
                                    class="text-sm font-weight-bold text-success">${{ number_format($client->available_credit, 2) }}</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <span
                                class="badge badge-lg {{ $client->status_badge }}">{{ $client->status_display_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection