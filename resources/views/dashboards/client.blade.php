@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Client Dashboard</h4>
                        <p class="text-sm text-secondary mb-0">Welcome back, {{ Auth::user()->name }}. Here's your account
                            overview.</p>
                    </div>
                    <div>
                        <a href="{{ route('client-portal.requests.create') }}" class="btn btn-primary btn-sm">
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalRequests ?? 0 }}</h5>
                                    <p class="mb-0 text-info text-sm">
                                        <span class="text-info font-weight-bolder">All time</span> requests
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">assignment</i>
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
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingRequests ?? 0 }}</h5>
                                    <p class="mb-0 text-warning text-sm">
                                        <span class="text-warning font-weight-bolder">Awaiting</span> approval
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">pending_actions</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Completed Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $completedRequests ?? 0 }}</h5>
                                    <p class="mb-0 text-success text-sm">
                                        <span class="text-success font-weight-bolder">Successfully</span> fulfilled
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">check_circle</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Available Credit</p>
                                    <h5 class="font-weight-bolder mb-0">TZS {{ number_format($availableCredit ?? 0) }}</h5>
                                    <p class="mb-0 text-info text-sm">
                                        <span class="text-info font-weight-bolder">Remaining</span> balance
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">account_balance_wallet</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="row">
            <!-- Recent Requests -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">schedule</i>
                            <h6 class="mb-0">Recent Fuel Requests</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Your latest fuel requests and their status</p>
                    </div>
                    <div class="card-body p-3">
                        @if($recentRequests && $recentRequests->count() > 0)
                            @foreach($recentRequests as $request)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="avatar avatar-sm bg-gradient-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'completed' ? 'success' : 'secondary') }} rounded-circle">
                                            <span class="text-white text-xs font-weight-bold">#{{ $request->id }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-sm font-weight-bold">
                                                    {{ $request->vehicle->plate_number ?? 'N/A' }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ number_format($request->quantity_requested, 2) }}L •
                                                    {{ ucfirst($request->fuel_type) }}</p>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i
                                                        class="material-symbols-rounded text-xs me-1">location_on</i>{{ $request->station->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'completed' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="material-symbols-rounded text-muted" style="font-size: 48px;">assignment</i>
                                <h6 class="text-muted mt-2">No recent requests</h6>
                                <p class="text-sm text-muted">You haven't made any fuel requests yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Credit Utilization & Quick Actions -->
            <div class="col-lg-4 mb-4">
                <!-- Credit Utilization -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">show_chart</i>
                            <h6 class="mb-0">Credit Utilization</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Your current credit usage</p>
                    </div>
                    <div class="card-body p-3">
                        @if(isset($creditUtilization))
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 text-sm font-weight-bold">
                                        {{ Auth::user()->client->company_name ?? 'Your Account' }}</h6>
                                    <span class="text-xs text-secondary">{{ number_format($creditUtilization, 1) }}%
                                        utilized</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-xs text-secondary">TZS
                                        {{ number_format(Auth::user()->client->current_balance ?? 0) }} / TZS
                                        {{ number_format(Auth::user()->client->credit_limit ?? 0) }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-gradient-{{ $creditUtilization > 90 ? 'danger' : ($creditUtilization > 75 ? 'warning' : 'success') }}"
                                        style="width: {{ $creditUtilization }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-2">
                                <p class="text-sm text-muted">Credit information not available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">build</i>
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Common tasks and tools</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('client-portal.requests.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="material-symbols-rounded me-2">add</i>New Fuel Request
                            </a>
                            <a href="{{ route('client-portal.requests.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">assignment</i>View All Requests
                            </a>
                            <a href="{{ route('client-portal.vehicles.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="material-symbols-rounded me-2">directions_car</i>Manage Vehicles
                            </a>
                            <a href="{{ route('client-portal.payments.index') }}" class="btn btn-outline-warning btn-sm">
                                <i class="material-symbols-rounded me-2">payments</i>Payment History
                            </a>
                            <a href="{{ route('client-portal.reports.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-2">analytics</i>Usage Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection