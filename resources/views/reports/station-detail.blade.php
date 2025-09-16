@extends('layouts.app')

@section('title', 'Station Detail Report')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ $station->name }} - Station Report</h4>
                        <p class="text-sm text-secondary mb-0">Detailed performance analysis for {{ $station->name }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('reports.all-stations') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">list</i>All Stations
                            </a>
                        @endif
                        <a href="{{ route('stations.inventory', $station->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="material-symbols-rounded me-2">inventory</i>Fuel Inventory
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="material-symbols-rounded me-2">arrow_back</i>Back to Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">From Date</label>
                                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">To Date</label>
                                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">filter_list</i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Station Overview Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">assignment</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Requests</p>
                            <h4 class="mb-0">{{ number_format($reports['total_requests']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">account_balance</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Credit Sales</p>
                            <h4 class="mb-0">TZS {{ number_format($reports['total_credit_sales']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">payments</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Actual Revenue</p>
                            <h4 class="mb-0">TZS {{ number_format($reports['total_actual_revenue']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">trending_up</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Avg Request Value</p>
                            <h4 class="mb-0">TZS {{ number_format($reports['avg_request_value']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Station Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">info</i>
                            <h6 class="mb-0">Station Information</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="text-secondary text-xs">Station Name</label>
                            <div class="text-sm font-weight-bold">{{ $station->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary text-xs">Location</label>
                            <div class="text-sm">{{ $station->city }}, {{ $station->region ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-secondary text-xs">Status</label>
                            <div class="text-sm">
                                <span
                                    class="badge badge-sm bg-gradient-{{ $station->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($station->status) }}
                                </span>
                            </div>
                        </div>
                        @if($station->manager)
                            <div class="mb-3">
                                <label class="text-secondary text-xs">Station Manager</label>
                                <div class="text-sm">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded text-primary me-2"
                                            style="font-size: 16px;">person</i>
                                        <div>
                                            <div class="font-weight-bold">{{ $station->manager->name }}</div>
                                            <small class="text-muted">{{ $station->manager->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">people</i>
                            <h6 class="mb-0">Station Staff</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if($reports['staff']->count() > 0)
                            @foreach($reports['staff'] as $staffMember)
                                <div class="d-flex align-items-center mb-2">
                                    <div
                                        class="avatar avatar-sm bg-gradient-{{ $staffMember->role === 'station_manager' ? 'primary' : 'info' }} rounded-circle me-3">
                                        <span
                                            class="text-white text-xs font-weight-bold">{{ substr($staffMember->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-sm">{{ $staffMember->name }}</h6>
                                        <p class="text-xs text-secondary mb-0">
                                            {{ ucfirst(str_replace('_', ' ', $staffMember->role)) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-sm">No staff assigned to this station.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">pie_chart</i>
                            <h6 class="mb-0">Request Status Breakdown</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if($reports['status_breakdown']->count() > 0)
                            @foreach($reports['status_breakdown'] as $status => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-sm text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                    <span class="badge badge-sm bg-gradient-info">{{ $count }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-sm">No requests found for the selected period.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">local_gas_station</i>
                            <h6 class="mb-0">Fuel Type Breakdown</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if($reports['fuel_type_breakdown']->count() > 0)
                            @foreach($reports['fuel_type_breakdown'] as $fuelType)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-sm text-capitalize">{{ $fuelType->fuel_type }}</span>
                                    <div class="text-end">
                                        <div class="text-sm font-weight-bold">{{ $fuelType->count }} requests</div>
                                        <div class="text-xs text-secondary">TZS {{ number_format($fuelType->revenue) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-sm">No fuel type data available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Trends -->
        @if($reports['daily_trends']->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">trending_up</i>
                                <h6 class="mb-0">Daily Trends</h6>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Requests</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Credit Sales</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Actual Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['daily_trends'] as $trend)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">
                                                                {{ \Carbon\Carbon::parse($trend->date)->format('M d, Y') }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-info">{{ $trend->requests }}</span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="text-secondary text-xs font-weight-bold">TZS
                                                        {{ number_format($trend->credit_sales) }}</span>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="text-success text-xs font-weight-bold">TZS
                                                        {{ number_format($trend->actual_revenue) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection