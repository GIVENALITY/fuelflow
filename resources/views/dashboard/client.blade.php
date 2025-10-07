<div class="row">
    <div class="col-12">
        <h2 class="mb-4">
            <i class="fas fa-building text-primary me-2"></i>Client Dashboard
        </h2>
    </div>
</div>

@if(Auth::user()->client)
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">TZS {{ number_format(Auth::user()->client->credit_limit, 0) }}</h4>
                            <p class="card-text">Credit Limit</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-credit-card fa-2x"></i>
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
                            <h4 class="card-title">TZS {{ number_format(Auth::user()->client->current_balance, 0) }}</h4>
                            <p class="card-text">Current Balance</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet fa-2x"></i>
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
                            <h4 class="card-title">{{ Auth::user()->client->vehicles()->count() }}</h4>
                            <p class="card-text">Total Vehicles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-truck fa-2x"></i>
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
                            <h4 class="card-title">{{ Auth::user()->client->fuelRequests()->count() }}</h4>
                            <p class="card-text">Total Orders</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
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
                    <h5><i class="fas fa-shopping-cart me-2"></i>Order Management</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.orders.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Create New Order
                        </a>
                        <a href="{{ route('client.orders.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                        <a href="{{ route('client.orders.bulk-upload') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-upload me-2"></i>Bulk Order Upload
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-credit-card me-2"></i>Payments</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('payments.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Submit Payment
                        </a>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-list me-2"></i>Payment History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($recentOrders) && $recentOrders->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-history me-2"></i>Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Vehicle</th>
                                        <th>Station</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->vehicle->plate_number }}</td>
                                            <td>{{ $order->station->name }}</td>
                                            <td>{{ $order->quantity_requested }}L</td>
                                            <td>TZS {{ number_format($order->total_amount, 0) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
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
@else
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Your client profile is not set up yet. Please contact the administrator.
    </div>
@endif
