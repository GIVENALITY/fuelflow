@extends('layouts.app')

@section('title', 'My Orders - FuelFlow')
@section('breadcrumb', 'My Orders')
@section('page-title', 'My Fuel Orders')

@push('styles')
<style>
.request-summary {
    position: relative;
}

.request-summary::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, #e9ecef 0%, #dee2e6 100%);
    z-index: 1;
}

.summary-item {
    position: relative;
    z-index: 2;
}

.summary-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.summary-icon:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.summary-icon i {
    font-size: 18px;
    font-weight: 500;
}

.summary-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 39px;
    top: 40px;
    width: 2px;
    height: calc(100% - 40px);
    background: #e9ecef;
    z-index: 1;
}

.summary-item:last-child::after {
    display: none;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>My Fuel Orders</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">{{ $orders->total() ?? 0 }}</span> total orders
                        </p>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <a href="{{ route('client-portal.requests.create') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">add</i> New Order
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Station</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel Type</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders ?? [] as $order)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $order->vehicle->plate_number ?? 'N/A' }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $order->vehicle->make ?? 'N/A' }} {{ $order->vehicle->model ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $order->station->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $order->station->city ?? 'N/A' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">{{ ucfirst($order->fuel_type) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $order->quantity_requested }} L</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">TZS {{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    @if($order->status === 'pending')
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    @elseif($order->status === 'approved')
                                        <span class="badge badge-sm bg-gradient-info">Approved</span>
                                    @elseif($order->status === 'in_progress')
                                        <span class="badge badge-sm bg-gradient-primary">In Progress</span>
                                    @elseif($order->status === 'dispensed')
                                        <span class="badge badge-sm bg-gradient-success">Dispensed</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge badge-sm bg-gradient-success">Completed</span>
                                    @elseif($order->status === 'rejected')
                                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $order->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('client-portal.requests.show', $order->id) }}" class="btn btn-link text-secondary mb-0">
                                        <i class="fa fa-eye text-xs me-2"></i>View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6 class="mb-0">Order Summary</h6>
            </div>
            <div class="card-body p-3">
                <div class="request-summary">
                    <!-- Pending Orders -->
                    <div class="summary-item mb-4">
                        <div class="d-flex align-items-center">
                            <div class="summary-icon bg-gradient-warning rounded-circle me-3">
                                <i class="material-symbols-rounded text-white">schedule</i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Pending Orders</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $orders->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Approved Orders -->
                    <div class="summary-item mb-4">
                        <div class="d-flex align-items-center">
                            <div class="summary-icon bg-gradient-info rounded-circle me-3">
                                <i class="material-symbols-rounded text-white">check_circle</i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Approved Orders</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $orders->where('status', 'approved')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completed Orders -->
                    <div class="summary-item mb-4">
                        <div class="d-flex align-items-center">
                            <div class="summary-icon bg-gradient-success rounded-circle me-3">
                                <i class="material-symbols-rounded text-white">local_gas_station</i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Completed Orders</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $orders->whereIn('status', ['completed', 'dispensed'])->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Orders -->
                    <div class="summary-item">
                        <div class="d-flex align-items-center">
                            <div class="summary-icon bg-gradient-primary rounded-circle me-3">
                                <i class="material-symbols-rounded text-white">analytics</i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Total Orders</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $orders->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
