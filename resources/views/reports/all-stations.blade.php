@extends('layouts.app')

@section('title', 'All Stations Report')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">All Stations Report</h4>
                        <p class="text-sm text-secondary mb-0">Comprehensive overview of all stations performance</p>
                    </div>
                    <div class="d-flex gap-2">
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

        <!-- System Totals -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">local_gas_station</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Stations</p>
                            <h4 class="mb-0">{{ $reports['system_totals']['total_stations'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">assignment</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Requests</p>
                            <h4 class="mb-0">{{ number_format($reports['system_totals']['total_requests']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">account_balance</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Revenue</p>
                            <h4 class="mb-0">TZS {{ number_format($reports['system_totals']['total_actual_revenue']) }}</h4>
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
                            <p class="text-sm mb-0 text-capitalize">Active Stations</p>
                            <h4 class="mb-0">{{ $reports['system_totals']['active_stations'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Station Performance Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">assessment</i>
                            <h6 class="mb-0">Station Performance</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Station</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Manager</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Requests</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Credit Sales</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Actual Revenue</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Avg Value</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports['station_reports'] as $stationReport)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $stationReport['station']->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $stationReport['station']->city ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    @if($stationReport['station']->manager)
                                                        {{ $stationReport['station']->manager->name }}
                                                    @else
                                                        <span class="text-warning">Not Assigned</span>
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span
                                                    class="badge badge-sm bg-gradient-info">{{ number_format($stationReport['total_requests']) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-secondary text-xs font-weight-bold">TZS
                                                    {{ number_format($stationReport['total_credit_sales']) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-success text-xs font-weight-bold">TZS
                                                    {{ number_format($stationReport['total_actual_revenue']) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-secondary text-xs font-weight-bold">TZS
                                                    {{ number_format($stationReport['avg_request_value']) }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('stations.inventory', $stationReport['station']->id) }}"
                                                        class="btn btn-outline-primary btn-sm" title="View Inventory">
                                                        <i class="material-symbols-rounded"
                                                            style="font-size: 16px;">inventory</i>
                                                    </a>
                                                    <a href="{{ route('reports.station-detail', $stationReport['station']->id) }}"
                                                        class="btn btn-outline-info btn-sm" title="View Report">
                                                        <i class="material-symbols-rounded"
                                                            style="font-size: 16px;">assessment</i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <p class="text-muted">No station data available for the selected period.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection