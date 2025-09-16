@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Reports & Analytics</h6>
                                <p class="text-sm text-secondary mb-0">Comprehensive insights into your fuel management
                                    operations</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="exportReport()">
                                    <i class="fas fa-download me-1"></i>
                                    Export
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="refreshData()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-4">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i>
                                        Apply Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Key Metrics -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Requests</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ number_format($reports['total_requests']) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-delivery-fast text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Credit Sales</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                TZS {{ number_format($reports['total_credit_sales'], 2) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Actual Revenue</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                TZS {{ number_format($reports['total_actual_revenue'], 2) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                            <i class="ni ni-chart-bar-32 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Success Rate</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                @if($reports['total_requests'] > 0)
                                                    {{ number_format(($reports['status_breakdown']['completed'] ?? 0) / $reports['total_requests'] * 100, 1) }}%
                                                @else
                                                    0%
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                            <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Status Breakdown Chart -->
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Request Status Breakdown</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart-container">
                                    <canvas id="statusChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Trends Chart -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Daily Trends</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart-container">
                                    <canvas id="trendsChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Categories -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Report Categories</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card bg-gradient-primary text-white h-100">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <i class="fas fa-cogs fa-2x mb-3"></i>
                                                    <h5 class="card-title">Operational</h5>
                                                    <p class="card-text">Station performance, fuel types, processing times
                                                    </p>
                                                    <a href="{{ route('reports.operational') }}"
                                                        class="btn btn-light btn-sm">
                                                        View Report
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card bg-gradient-success text-white h-100">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <i class="fas fa-dollar-sign fa-2x mb-3"></i>
                                                    <h5 class="card-title">Financial</h5>
                                                    <p class="card-text">Revenue, payments, outstanding balances</p>
                                                    <a href="{{ route('reports.financial') }}" class="btn btn-light btn-sm">
                                                        View Report
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card bg-gradient-info text-white h-100">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <i class="fas fa-users fa-2x mb-3"></i>
                                                    <h5 class="card-title">Client Analytics</h5>
                                                    <p class="card-text">Client performance, credit utilization</p>
                                                    <a href="{{ route('reports.client-analytics') }}"
                                                        class="btn btn-light btn-sm">
                                                        View Report
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-4">
                                        <div class="card bg-gradient-warning text-white h-100">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <i class="fas fa-gas-pump fa-2x mb-3"></i>
                                                    <h5 class="card-title">Station Reports</h5>
                                                    <p class="card-text">Station performance, inventory levels</p>
                                                    <a href="{{ route('reports.station') }}" class="btn btn-light btn-sm">
                                                        View Report
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Status Breakdown Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($reports['status_breakdown']);
        const statusLabels = Object.keys(statusData);
        const statusValues = Object.values(statusData);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1).replace('_', ' ')),
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#e74c3c', // Red for rejected
                        '#f39c12', // Orange for pending
                        '#3498db', // Blue for approved
                        '#2ecc71', // Green for completed
                        '#9b59b6', // Purple for in_progress
                        '#34495e'  // Dark for dispensed
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Daily Trends Chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        const trendsData = @json($reports['daily_trends']);
        const trendsLabels = trendsData.map(item => item.date);
        const trendsRequests = trendsData.map(item => item.requests);
        const trendsRevenue = trendsData.map(item => item.revenue);

        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: trendsLabels,
                datasets: [{
                    label: 'Requests',
                    data: trendsRequests,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: 'Revenue ($)',
                    data: trendsRevenue,
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        function exportReport() {
            // Implement export functionality
            alert('Export functionality will be implemented');
        }

        function refreshData() {
            location.reload();
        }
    </script>
@endsection