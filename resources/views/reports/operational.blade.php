@extends('layouts.app')

@section('title', 'Operational Reports')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Operational Reports</h4>
                        <p class="text-sm text-secondary mb-0">Station performance, fuel types, and processing times</p>
                    </div>
                    <div>
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
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-sm">From Date</label>
                                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-sm">To Date</label>
                                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">filter_list</i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fuel Type Breakdown -->
        @if(isset($reports['fuel_type_breakdown']) && $reports['fuel_type_breakdown']->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">local_gas_station</i>
                                <h6 class="mb-0">Fuel Type Breakdown</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel
                                                Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Requests</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Avg
                                                per Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['fuel_type_breakdown'] as $fuelType)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="icon icon-shape bg-gradient-info shadow text-center rounded-circle me-3">
                                                            <i
                                                                class="material-symbols-rounded text-lg opacity-10">local_gas_station</i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ ucfirst($fuelType->fuel_type) }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">{{ number_format($fuelType->count) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">{{ number_format($fuelType->total_quantity, 2) }}L</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm">{{ number_format($fuelType->total_quantity / $fuelType->count, 2) }}L</span>
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

        <!-- Station Performance -->
        @if(isset($reports['station_performance']) && $reports['station_performance']->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">analytics</i>
                                <h6 class="mb-0">Station Performance</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Station</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Requests</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Revenue</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Avg
                                                per Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['station_performance'] as $station)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle me-3">
                                                            <i
                                                                class="material-symbols-rounded text-lg opacity-10">local_gas_station</i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ $station->name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">{{ number_format($station->requests) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">${{ number_format($station->revenue, 2) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm">${{ number_format($station->revenue / $station->requests, 2) }}</span>
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

        @if(
                (!isset($reports['fuel_type_breakdown']) || $reports['fuel_type_breakdown']->count() == 0) &&
                (!isset($reports['station_performance']) || $reports['station_performance']->count() == 0)
            )
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="material-symbols-rounded text-muted" style="font-size: 4rem;">analytics</i>
                            <h5 class="mt-3 text-muted">No Operational Data Found</h5>
                            <p class="text-muted">No operational data available for the selected date range.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2">dashboard</i>Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection