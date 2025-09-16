@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Admin Dashboard</h4>
                        <p class="text-sm text-secondary mb-0">System overview and performance metrics</p>
                    </div>
                    <div>
                        <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded me-2">add</i>Add Client
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
                                    <h5 class="font-weight-bolder mb-0">{{ $activeClients ?? 0 }}</h5>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Stations</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $totalStations ?? 0 }}</h5>
                                    <p class="mb-0 text-success text-sm">
                                        <span class="text-success font-weight-bolder">+2</span> this month
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Revenue</p>
                                    <h5 class="font-weight-bolder mb-0">TZS {{ number_format($totalRevenue ?? 0) }}</h5>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Approvals</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingApprovals ?? 0 }}</h5>
                                    <p class="mb-0 text-warning text-sm">
                                        <span class="text-warning font-weight-bolder">Requires attention</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
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
            <!-- Recent Fuel Requests -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">schedule</i>
                            <h6 class="mb-0">Recent System Activity</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Latest fuel requests across all stations</p>
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
                                                        class="material-symbols-rounded text-xs me-1">location_on</i>{{ $request->station->name ?? 'N/A' }}
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
                                <h6 class="text-muted mt-2">No recent activity</h6>
                                <p class="text-sm text-muted">No fuel requests found in the system.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">settings</i>
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">System management tools</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('clients.create') }}" class="btn btn-outline-primary btn-sm">
                                <i class="material-symbols-rounded me-2">person_add</i>Add New Client
                            </a>
                            <a href="{{ route('stations.create') }}" class="btn btn-outline-warning btn-sm">
                                <i class="material-symbols-rounded me-2">add_location</i>Add New Station
                            </a>
                            <a href="{{ route('users.create') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">person_add</i>Add New User
                            </a>
                            <a href="{{ route('fuel-prices.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="material-symbols-rounded me-2">local_gas_station</i>Update Fuel Prices
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-2">analytics</i>View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection