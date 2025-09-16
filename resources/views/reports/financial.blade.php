@extends('layouts.app')

@section('title', 'Financial Reports')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Financial Reports</h4>
                        <p class="text-sm text-secondary mb-0">Revenue, payments, and outstanding balances</p>
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

        <!-- Financial Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Revenue</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        ${{ number_format($reports['total_revenue'] ?? 0, 2) }}</h5>
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
            <div class="col-xl-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Payments</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        ${{ number_format($reports['total_payments'] ?? 0, 2) }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">payment</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Outstanding Balance</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        ${{ number_format($reports['outstanding_balance'] ?? 0, 2) }}</h5>
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

        <!-- Financial Analysis -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">analytics</i>
                            <h6 class="mb-0">Financial Analysis</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h6 class="text-sm font-weight-bold mb-2">Revenue vs Payments</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Revenue Generated:</span>
                                        <span
                                            class="text-sm font-weight-bold text-success">${{ number_format($reports['total_revenue'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Payments Received:</span>
                                        <span
                                            class="text-sm font-weight-bold text-info">${{ number_format($reports['total_payments'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Collection Rate:</span>
                                        <span class="text-sm font-weight-bold">
                                            @if(($reports['total_revenue'] ?? 0) > 0)
                                                {{ number_format((($reports['total_payments'] ?? 0) / ($reports['total_revenue'] ?? 1)) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h6 class="text-sm font-weight-bold mb-2">Outstanding Balance</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Total Outstanding:</span>
                                        <span
                                            class="text-sm font-weight-bold text-warning">${{ number_format($reports['outstanding_balance'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-sm">Percentage of Revenue:</span>
                                        <span class="text-sm font-weight-bold">
                                            @if(($reports['total_revenue'] ?? 0) > 0)
                                                {{ number_format((($reports['outstanding_balance'] ?? 0) / ($reports['total_revenue'] ?? 1)) * 100, 1) }}%
                                            @else
                                                0%
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection