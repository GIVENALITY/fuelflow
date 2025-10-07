<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-crown text-warning me-2"></i>SuperAdmin Dashboard
        </h2>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_users'] ?? 0 }}</h4>
                        <p class="card-text">Total Users</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
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
                        <p class="card-text">Total Clients</p>
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
                        <h4 class="card-title">{{ $stats['pending_applications'] ?? 0 }}</h4>
                        <p class="card-text">Pending Applications</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tools me-2"></i>System Management</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('super-admin.stations.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-gas-pump me-2"></i>Manage Stations
                    </a>
                    <a href="{{ route('super-admin.users.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Manage Users
                    </a>
                    <a href="{{ route('super-admin.reports.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>System Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $stats['active_orders'] ?? 0 }}</h4>
                        <small class="text-muted">Active Orders</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $stats['pending_payments'] ?? 0 }}</h4>
                        <small class="text-muted">Pending Payments</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
