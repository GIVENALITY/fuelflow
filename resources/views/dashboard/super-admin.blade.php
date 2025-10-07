<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-crown text-warning me-2"></i>Super Admin Dashboard
        </h2>
        <p class="text-muted">Manage fuel station businesses and platform operations</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_businesses'] ?? 0 }}</h4>
                        <p class="card-text">Total Businesses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['pending_businesses'] ?? 0 }}</h4>
                        <p class="card-text">Pending Approvals</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_stations'] ?? 0 }}</h4>
                        <p class="card-text">Total Stations</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-gas-pump fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_clients'] ?? 0 }}</h4>
                        <p class="card-text">Total Fleet Owners</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-truck fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-building me-2"></i>Business Management</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('super-admin.businesses.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-building me-2"></i>Manage Businesses
                            <small class="d-block text-muted">Review and approve fuel station companies</small>
                        </div>
                        <span class="badge bg-warning">{{ $stats['pending_businesses'] ?? 0 }} Pending</span>
                    </a>
                    <a href="{{ route('super-admin.businesses.create') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i>Add New Business
                        <small class="d-block text-muted">Create account for new fuel station company</small>
                    </a>
                    <a href="{{ route('super-admin.reports.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Platform Reports
                        <small class="d-block text-muted">View system-wide analytics and performance</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Platform Overview</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <h4 class="text-success">{{ $stats['active_businesses'] ?? 0 }}</h4>
                        <small class="text-muted">Active Businesses</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-primary">{{ $stats['total_revenue'] ?? 0 }}</h4>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-info">{{ $stats['monthly_sales'] ?? 0 }}</h4>
                        <small class="text-muted">Monthly Sales (L)</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $stats['avg_stations_per_business'] ?? 0 }}</h4>
                        <small class="text-muted">Avg Stations/Business</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
