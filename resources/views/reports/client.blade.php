@extends('layouts.app')

@section('title', 'My Reports')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">My Reports</h4>
                        <p class="text-sm text-secondary mb-0">Your personal fuel request analytics and spending insights
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('client-portal.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="material-symbols-rounded me-2">arrow_back</i>Back to Dashboard
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

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $reports['total_requests'] ?? 0 }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">request_quote</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Credit Spending</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        TZS {{ number_format($reports['total_credit_spending'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle">
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Payments Made</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        TZS {{ number_format($reports['total_actual_payments'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">trending_up</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Credit Usage</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ number_format($reports['credit_utilization'] ?? 0, 1) }}%
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">account_balance</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Status Breakdown -->
        @if(isset($reports['status_breakdown']) && $reports['status_breakdown']->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">pie_chart</i>
                                <h6 class="mb-0">Request Status Breakdown</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                @foreach($reports['status_breakdown'] as $status => $count)
                                    <div class="col-md-3 col-sm-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="icon icon-shape bg-gradient-{{ $status === 'completed' ? 'success' : ($status === 'pending' ? 'warning' : 'info') }} shadow text-center rounded-circle me-3">
                                                <i class="material-symbols-rounded text-lg opacity-10">
                                                    {{ $status === 'completed' ? 'check_circle' : ($status === 'pending' ? 'pending' : 'info') }}
                                                </i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-sm">{{ ucfirst($status) }}</h6>
                                                <span class="text-sm font-weight-bold">{{ $count }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Fuel Type Breakdown -->
        @if(isset($reports['fuel_type_breakdown']) && $reports['fuel_type_breakdown']->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">local_gas_station</i>
                                <h6 class="mb-0">Fuel Type Usage</h6>
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
                                                Requests</th>
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

        @if(
                (!isset($reports['status_breakdown']) || $reports['status_breakdown']->count() == 0) &&
                (!isset($reports['fuel_type_breakdown']) || $reports['fuel_type_breakdown']->count() == 0)
            )
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="material-symbols-rounded text-muted" style="font-size: 4rem;">analytics</i>
                            <h5 class="mt-3 text-muted">No Data Available</h5>
                            <p class="text-muted">No fuel request data available for the selected date range.</p>
                            <a href="{{ route('client-portal.requests.create') }}" class="btn btn-primary">
                                <i class="material-symbols-rounded me-2">add</i>Create New Request
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection