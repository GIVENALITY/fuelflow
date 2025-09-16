@extends('layouts.app')

@section('title', 'Client Analytics')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Client Analytics</h4>
                        <p class="text-sm text-secondary mb-0">Client performance and credit utilization</p>
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

        <!-- Client Performance -->
        @if(isset($reports['client_performance']) && $reports['client_performance']->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <i class="material-symbols-rounded text-primary me-2">groups</i>
                                <h6 class="mb-0">Client Performance</h6>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Client</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Requests</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Revenue</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Avg
                                                per Request</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reports['client_performance'] as $client)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle me-3">
                                                            <i class="material-symbols-rounded text-lg opacity-10">business</i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 text-sm">{{ $client->company_name }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-sm bg-gradient-{{ $client->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($client->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">{{ number_format($client->total_requests) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">${{ number_format($client->total_revenue, 2) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm">${{ number_format($client->total_revenue / $client->total_requests, 2) }}</span>
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

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Clients</p>
                                        <h5 class="font-weight-bolder mb-0">{{ $reports['client_performance']->count() }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center rounded-circle">
                                        <i class="material-symbols-rounded text-lg opacity-10">groups</i>
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
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ number_format($reports['client_performance']->sum('total_requests')) }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-success shadow text-center rounded-circle">
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
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Revenue</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            ${{ number_format($reports['client_performance']->sum('total_revenue'), 2) }}</h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle">
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
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Avg per Client</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            ${{ number_format($reports['client_performance']->sum('total_revenue') / $reports['client_performance']->count(), 2) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center rounded-circle">
                                        <i class="material-symbols-rounded text-lg opacity-10">trending_up</i>
                                    </div>
                                </div>
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
                            <i class="material-symbols-rounded text-muted" style="font-size: 4rem;">groups</i>
                            <h5 class="mt-3 text-muted">No Client Data Found</h5>
                            <p class="text-muted">No client performance data available for the selected date range.</p>
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