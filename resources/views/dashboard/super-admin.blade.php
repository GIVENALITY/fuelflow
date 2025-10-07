<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1 font-weight-bold">Platform Overview</h4>
                <p class="text-sm text-muted mb-0">Manage fuel station businesses and platform operations</p>
            </div>
            <div class="text-right">
                <span class="badge badge-sm bg-gradient-success">{{ $stats['active_businesses'] ?? 0 }} Active</span>
                <span class="badge badge-sm bg-gradient-warning ms-2">{{ $stats['pending_businesses'] ?? 0 }} Pending</span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-sm font-weight-bold text-uppercase mb-1 text-muted">Total Businesses</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_businesses'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-light text-dark rounded-circle shadow">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-sm font-weight-bold text-uppercase mb-1 text-muted">Total Stations</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_stations'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-light text-dark rounded-circle shadow">
                            <i class="fas fa-gas-pump"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-sm font-weight-bold text-uppercase mb-1 text-muted">Fleet Owners</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $stats['total_clients'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-light text-dark rounded-circle shadow">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-sm font-weight-bold text-uppercase mb-1 text-muted">Monthly Revenue</div>
                        <div class="h5 mb-0 font-weight-bold">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon icon-shape bg-light text-dark rounded-circle shadow">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 font-weight-bold">Business Management</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('super-admin.businesses.index') }}" class="list-group-item list-group-item-action border-0 py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-sm icon-shape bg-light text-dark rounded-circle me-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 font-weight-bold">Manage Businesses</h6>
                                <p class="text-sm text-muted mb-0">Review and approve fuel station companies</p>
                            </div>
                            <div class="text-end">
                                <span class="badge badge-sm bg-gradient-warning">{{ $stats['pending_businesses'] ?? 0 }} Pending</span>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('super-admin.businesses.create') }}" class="list-group-item list-group-item-action border-0 py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-sm icon-shape bg-light text-dark rounded-circle me-3">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 font-weight-bold">Add New Business</h6>
                                <p class="text-sm text-muted mb-0">Create account for new fuel station company</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('super-admin.reports.index') }}" class="list-group-item list-group-item-action border-0 py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-sm icon-shape bg-light text-dark rounded-circle me-3">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 font-weight-bold">Platform Reports</h6>
                                <p class="text-sm text-muted mb-0">View system-wide analytics and performance</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 font-weight-bold">Performance Metrics</h6>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h6 mb-1 font-weight-bold">{{ number_format($stats['monthly_sales'] ?? 0, 0) }}L</div>
                            <div class="text-xs text-muted">Monthly Sales</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center">
                            <div class="h6 mb-1 font-weight-bold">{{ number_format($stats['avg_stations_per_business'] ?? 0, 1) }}</div>
                            <div class="text-xs text-muted">Avg Stations/Business</div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-sm font-weight-bold">Platform Status</span>
                    <span class="badge badge-sm bg-gradient-success">Operational</span>
                </div>
                
                <div class="mt-2">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 85%"></div>
                    </div>
                    <div class="text-xs text-muted mt-1">System Performance: 85%</div>
                </div>
            </div>
        </div>
    </div>
</div>
