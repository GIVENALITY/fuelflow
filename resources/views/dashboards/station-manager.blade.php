@extends('layouts.app')

@section('title', 'Station Manager Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Station Manager Dashboard</h4>
                        <p class="text-sm text-secondary mb-0">Welcome back, {{ Auth::user()->name }}. Here's your station
                            overview.</p>
                    </div>
                    <div>
                        <a href="{{ route('fuel-requests.pending') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded me-2">pending_actions</i>Review Pending
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $todayRequests ?? 0 }}</h5>
                                    <p class="mb-0 text-success text-sm">
                                        <span class="text-success font-weight-bolder">Active</span> requests
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Fuel Dispensed Today</p>
                                    <h5 class="font-weight-bolder mb-0">{{ number_format($fuelDispensedToday ?? 0, 2) }}L
                                    </h5>
                                    <p class="mb-0 text-success text-sm">
                                        <span class="text-success font-weight-bolder">Dispensed</span> today
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">local_gas_station</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Available Staff</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $availableStaff ?? 0 }}</h5>
                                    <p class="mb-0 text-info text-sm">
                                        <span class="text-info font-weight-bolder">Active</span> pumpers
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Approval</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingRequests ?? 0 }}</h5>
                                    <p class="mb-0 text-warning text-sm">
                                        <span class="text-warning font-weight-bolder">Awaiting</span> your review
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
        </div>

        <!-- Content Sections -->
        <div class="row">
            <!-- Recent Station Requests -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">schedule</i>
                            <h6 class="mb-0">Recent Station Requests</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Latest fuel requests for your station</p>
                    </div>
                    <div class="card-body p-3">
                        @if($recentRequests && $recentRequests->count() > 0)
                            @foreach($recentRequests as $request)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="avatar avatar-sm bg-gradient-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'secondary') }} rounded-circle">
                                            <span class="text-white text-xs font-weight-bold">#{{ $request->id }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-sm font-weight-bold">
                                                    {{ $request->client->company_name ?? 'N/A' }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $request->vehicle->plate_number ?? 'N/A' }} •
                                                    {{ number_format($request->quantity_requested, 2) }}L</p>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i
                                                        class="material-symbols-rounded text-xs me-1">schedule</i>{{ $request->request_date ? $request->request_date->format('M d, Y H:i') : 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="material-symbols-rounded text-muted" style="font-size: 48px;">inbox</i>
                                <h6 class="text-muted mt-2">No recent requests</h6>
                                <p class="text-sm text-muted">No fuel requests found for your station.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Station Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">build</i>
                            <h6 class="mb-0">Station Actions</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Manage your station operations</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('fuel-requests.pending') }}" class="btn btn-outline-warning btn-sm">
                                <i class="material-symbols-rounded me-2">pending_actions</i>Review Pending Requests
                            </a>
                            <a href="{{ route('fuel-requests.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="material-symbols-rounded me-2">assignment</i>View All Requests
                            </a>
                            <a href="{{ route('stations.inventory', Auth::user()->station_id) }}"
                                class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">inventory</i>Check Fuel Inventory
                            </a>
                            <a href="{{ route('receipts.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="material-symbols-rounded me-2">receipt_long</i>Manage Receipts
                            </a>
                            <a href="{{ route('reports.station') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-2">analytics</i>Station Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection