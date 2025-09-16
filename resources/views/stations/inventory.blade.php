@extends('layouts.app')

@section('title', 'Fuel Inventory')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Fuel Inventory</h4>
                        <p class="text-sm text-secondary mb-0">{{ $station->name }} - Current fuel levels and stock status
                        </p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        @if(auth()->user()->isAdmin())
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-rounded me-2">swap_horiz</i>
                                    Switch Station ({{ $allStations->count() }})
                                </button>
                                <ul class="dropdown-menu">
                                    @if($allStations->count() > 0)
                                        @foreach($allStations as $stationOption)
                                            <li>
                                                <a class="dropdown-item {{ $stationOption->id == $station->id ? 'active' : '' }}"
                                                    href="{{ route('stations.inventory', $stationOption->id) }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>{{ $stationOption->name }}</span>
                                                        @if($stationOption->id == $station->id)
                                                            <i class="material-symbols-rounded text-primary"
                                                                style="font-size: 16px;">check</i>
                                                        @endif
                                                    </div>
                                                    @php
                                                        $optionManager = \App\Models\User::where('station_id', $stationOption->id)
                                                            ->where('role', 'station_manager')
                                                            ->first();
                                                    @endphp
                                                    @if($optionManager)
                                                        <small class="text-muted d-block">{{ $optionManager->name }}</small>
                                                    @else
                                                        <small class="text-warning d-block">No Manager</small>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li><span class="dropdown-item-text text-muted">No stations available</span></li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('stations.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">list</i>All Stations
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="material-symbols-rounded me-2">arrow_back</i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Cards -->
        <div class="row mb-4">
            @foreach($inventory as $fuelType => $data)
                <div class="col-xl-6 col-lg-12 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i
                                    class="material-symbols-rounded text-{{ $data['status'] === 'low' ? 'danger' : 'success' }} me-2">
                                    {{ $fuelType === 'diesel' ? 'local_gas_station' : 'local_gas_station' }}
                                </i>
                                <h6 class="mb-0">{{ ucfirst($fuelType) }} Inventory</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Current Level</p>
                                        <h5 class="font-weight-bolder mb-0">{{ number_format($data['current_level']) }}L</h5>
                                        <p class="mb-0 text-{{ $data['status'] === 'low' ? 'danger' : 'success' }} text-sm">
                                            <span
                                                class="text-{{ $data['status'] === 'low' ? 'danger' : 'success' }} font-weight-bolder">
                                                {{ $data['status'] === 'low' ? 'Low Stock' : 'Good Level' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-{{ $data['status'] === 'low' ? 'danger' : 'success' }} shadow-{{ $data['status'] === 'low' ? 'danger' : 'success' }} text-center rounded-circle">
                                        <i class="material-symbols-rounded text-lg opacity-10">local_gas_station</i>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-xs text-secondary">Capacity:
                                        {{ number_format($data['capacity']) }}L</span>
                                    <span
                                        class="text-xs text-secondary">{{ number_format(($data['current_level'] / $data['capacity']) * 100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-gradient-{{ $data['status'] === 'low' ? 'danger' : 'success' }}"
                                        style="width: {{ ($data['current_level'] / $data['capacity']) * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="material-symbols-rounded text-xs me-1">schedule</i>
                                    Last updated: {{ $data['last_updated']->format('M d, Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Station Information -->
        @if(auth()->user()->isAdmin() || auth()->user()->isStationManager())
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">info</i>
                                <h6 class="mb-0">Station Information</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Station Name</label>
                                        <div class="text-sm font-weight-bold">{{ $station->name }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Location</label>
                                        <div class="text-sm">{{ $station->city }}, {{ $station->region ?? 'N/A' }}</div>
                                    </div>
                                    @if(auth()->user()->isAdmin())
                                        <div class="mb-3">
                                            <label class="text-secondary text-xs">Station ID</label>
                                            <div class="text-sm font-weight-bold">#{{ $station->id }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Status</label>
                                        <div class="text-sm">
                                            <span
                                                class="badge badge-sm bg-gradient-{{ $station->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($station->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Station Manager</label>
                                        <div class="text-sm">
                                            @if($stationManager)
                                                <div class="d-flex align-items-center">
                                                    <i class="material-symbols-rounded text-primary me-2"
                                                        style="font-size: 16px;">person</i>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $stationManager->name }}</div>
                                                        <small class="text-muted">{{ $stationManager->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-warning">
                                                    <i class="material-symbols-rounded me-1" style="font-size: 16px;">warning</i>
                                                    Not Assigned
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Fuel Pumpers</label>
                                        <div class="text-sm">
                                            @php
                                                $pumpers = \App\Models\User::where('station_id', $station->id)
                                                    ->where('role', 'fuel_pumper')
                                                    ->get();
                                            @endphp
                                            @if($pumpers->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($pumpers as $pumper)
                                                        <span class="badge badge-sm bg-gradient-info">{{ $pumper->name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted">No active pumpers assigned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Limited Station Information for Fuel Pumpers -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">info</i>
                                <h6 class="mb-0">Station Information</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Station Name</label>
                                        <div class="text-sm font-weight-bold">{{ $station->name }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Location</label>
                                        <div class="text-sm">{{ $station->city }}, {{ $station->region ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Your Role</label>
                                        <div class="text-sm">
                                            <span class="badge badge-sm bg-gradient-info">Fuel Pumper</span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-secondary text-xs">Assigned Station</label>
                                        <div class="text-sm font-weight-bold">{{ $station->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">build</i>
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex gap-2 flex-wrap">
                            @if(auth()->user()->isAdmin() || auth()->user()->isStationManager())
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="material-symbols-rounded me-2">add</i>Update Inventory
                                </button>
                                <button class="btn btn-outline-warning btn-sm">
                                    <i class="material-symbols-rounded me-2">local_gas_station</i>Request Restock
                                </button>
                                <a href="{{ route('fuel-requests.pending') }}" class="btn btn-outline-info btn-sm">
                                    <i class="material-symbols-rounded me-2">pending_actions</i>View Pending Requests
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('reports.all-stations') }}" class="btn btn-outline-success btn-sm">
                                        <i class="material-symbols-rounded me-2">analytics</i>All Stations Report
                                    </a>
                                    <a href="{{ route('reports.station-detail', $station->id) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="material-symbols-rounded me-2">assessment</i>This Station Report
                                    </a>
                                @else
                                    <a href="{{ route('reports.station') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="material-symbols-rounded me-2">analytics</i>Station Reports
                                    </a>
                                @endif
                            @else
                                <!-- Limited actions for fuel pumpers -->
                                <a href="{{ route('fuel-requests.my-assignments') }}" class="btn btn-outline-info btn-sm">
                                    <i class="material-symbols-rounded me-2">assignment_ind</i>My Assignments
                                </a>
                                <a href="{{ route('reports.my-activity') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="material-symbols-rounded me-2">analytics</i>My Activity
                                </a>
                                <button class="btn btn-outline-warning btn-sm" disabled>
                                    <i class="material-symbols-rounded me-2">local_gas_station</i>Request Restock
                                    <small class="d-block text-muted">Contact Manager</small>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection