@extends('layouts.app')

@section('title', 'My Activity Reports')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">My Activity Reports</h4>
                        <p class="text-sm text-secondary mb-0">Your personal activity and performance metrics</p>
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

        <!-- Activity Summary -->
        <div class="row mb-4">
            <div class="col-xl-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $reports['my_requests']->count() ?? 0 }}</h5>
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
            <div class="col-xl-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Spending</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        TSH {{ number_format($reports['monthly_spending'] ?? 0, 2) }}</h5>
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
        </div>

        <!-- My Requests -->
        @if(isset($reports['my_requests']) && $reports['my_requests']->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">list_alt</i>
                                <h6 class="mb-0">My Requests</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Request ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Vehicle</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel
                                                Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['my_requests'] as $request)
                                            <tr>
                                                <td>
                                                    <span class="text-sm font-weight-bold">#{{ $request->id }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="icon icon-shape bg-gradient-info shadow text-center rounded-circle me-3">
                                                            <i
                                                                class="material-symbols-rounded text-lg opacity-10">directions_car</i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ $request->vehicle->plate_number ?? 'N/A' }}
                                                            </h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $request->vehicle->make ?? '' }}
                                                                {{ $request->vehicle->model ?? '' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ ucfirst($request->fuel_type) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">{{ number_format($request->quantity_requested, 2) }}L</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-bold">TSH
                                                        {{ number_format($request->total_amount, 2) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-sm bg-gradient-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ $request->created_at->format('M d, Y') }}</span>
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
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="material-symbols-rounded text-muted" style="font-size: 4rem;">person</i>
                            <h5 class="mt-3 text-muted">No Activity Found</h5>
                            <p class="text-muted">No activity data available for the selected date range.</p>
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