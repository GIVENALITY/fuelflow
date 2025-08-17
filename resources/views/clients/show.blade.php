@extends('layouts.app')

@section('title', 'Client Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Client Details</h6>
                            </div>
                            <div class="col-6 text-end">
                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-light me-2">
                                    <i class="material-symbols-rounded">edit</i> Edit
                                </a>
                                @endif
                                <a href="{{ route('clients.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Client Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Basic Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-secondary">Company Name:</td>
                                    <td class="font-weight-bold">{{ $client->company_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Contact Person:</td>
                                    <td class="font-weight-bold">{{ $client->contact_person }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Email:</td>
                                    <td class="font-weight-bold">{{ $client->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Phone:</td>
                                    <td class="font-weight-bold">{{ $client->phone }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Financial Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-secondary">Credit Limit:</td>
                                    <td class="font-weight-bold">{{ number_format($client->credit_limit) }} TZS</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Current Balance:</td>
                                    <td class="font-weight-bold {{ $client->current_balance > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($client->current_balance) }} TZS
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Available Credit:</td>
                                    <td class="font-weight-bold text-success">
                                        {{ number_format($client->credit_limit - $client->current_balance) }} TZS
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Status:</td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $client->status === 'active' ? 'success' : ($client->status === 'suspended' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($client->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Address:</td>
                                    <td class="font-weight-bold">{{ $client->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Vehicles</h6>
                                            <h4 class="mb-0">{{ $client->vehicles->count() }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="material-symbols-rounded text-white opacity-10">directions_car</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Total Requests</h6>
                                            <h4 class="mb-0">{{ $client->fuelRequests->count() }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="material-symbols-rounded text-white opacity-10">local_gas_station</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-info text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Payments</h6>
                                            <h4 class="mb-0">{{ $client->payments->count() }}</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="material-symbols-rounded text-white opacity-10">payments</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-warning text-white">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Credit Usage</h6>
                                            <h4 class="mb-0">{{ number_format(($client->current_balance / $client->credit_limit) * 100, 1) }}%</h4>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="material-symbols-rounded text-white opacity-10">account_balance_wallet</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="vehicles-tab" data-bs-toggle="tab" data-bs-target="#vehicles" type="button" role="tab">
                                <i class="material-symbols-rounded me-2">directions_car</i>Vehicles
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests" type="button" role="tab">
                                <i class="material-symbols-rounded me-2">local_gas_station</i>Fuel Requests
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments" type="button" role="tab">
                                <i class="material-symbols-rounded me-2">payments</i>Payments
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="clientTabsContent">
                        <!-- Vehicles Tab -->
                        <div class="tab-pane fade show active" id="vehicles" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Client Vehicles</h6>
                                @if(Auth::user()->isAdmin())
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                    <i class="material-symbols-rounded me-2">add</i>Add Vehicle
                                </button>
                                @endif
                            </div>
                            
                            @if($client->vehicles->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fuel Level</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->vehicles as $vehicle)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $vehicle->make }} {{ $vehicle->model }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $vehicle->plate_number }} ({{ $vehicle->year }})</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-xs font-weight-bold me-2">{{ $vehicle->current_fuel_level }}%</span>
                                                    <div class="progress" style="width: 60px; height: 6px;">
                                                        <div class="progress-bar bg-gradient-{{ $vehicle->current_fuel_level < 20 ? 'danger' : ($vehicle->current_fuel_level < 50 ? 'warning' : 'success') }}" 
                                                             style="width: {{ $vehicle->current_fuel_level }}%"></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $vehicle->status === 'active' ? 'success' : ($vehicle->status === 'maintenance' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($vehicle->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(Auth::user()->isAdmin())
                                                <button type="button" class="btn btn-link text-secondary mb-0 btn-sm" 
                                                        onclick="editVehicle({{ $vehicle->id }}, '{{ $vehicle->plate_number }}', '{{ $vehicle->make }}', '{{ $vehicle->model }}', {{ $vehicle->year }}, '{{ $vehicle->fuel_type }}', {{ $vehicle->tank_capacity }}, {{ $vehicle->current_fuel_level }}, '{{ $vehicle->status }}')">
                                                    <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <p class="text-sm text-secondary mb-0">No vehicles found for this client.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Requests Tab -->
                        <div class="tab-pane fade" id="requests" role="tabpanel">
                            <h6 class="mb-3">Recent Fuel Requests</h6>
                            @if($client->fuelRequests->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vehicle</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->fuelRequests->take(10) as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">REQ-{{ str_pad($request->id, 3, '0', STR_PAD_LEFT) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->vehicle->plate_number }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ number_format($request->amount) }} TZS</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $request->status === 'approved' ? 'success' : ($request->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <p class="text-sm text-secondary mb-0">No fuel requests found for this client.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Payments Tab -->
                        <div class="tab-pane fade" id="payments" role="tabpanel">
                            <h6 class="mb-3">Payment History</h6>
                            @if($client->payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Method</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->payments->take(10) as $payment)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">PAY-{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ number_format($payment->amount) }} TZS</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $payment->created_at->format('M d, Y') }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <p class="text-sm text-secondary mb-0">No payments found for this client.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
@if(Auth::user()->isAdmin())
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">Add New Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('clients.vehicles.add', $client) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Plate Number *</label>
                                <input type="text" class="form-control" name="plate_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Make *</label>
                                <input type="text" class="form-control" name="make" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Model *</label>
                                <input type="text" class="form-control" name="model" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Year *</label>
                                <input type="number" class="form-control" name="year" min="1900" max="{{ date('Y') + 1 }}" value="{{ date('Y') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Fuel Type *</label>
                                <select class="form-control" name="fuel_type" required>
                                    <option value="">Select Fuel Type</option>
                                    <option value="petrol">Petrol</option>
                                    <option value="diesel">Diesel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Tank Capacity (L) *</label>
                                <input type="number" class="form-control" name="tank_capacity" min="0" step="0.1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Current Fuel Level (%) *</label>
                                <input type="number" class="form-control" name="current_fuel_level" min="0" max="100" value="50" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Status *</label>
                                <select class="form-control" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function editVehicle(id, plateNumber, make, model, year, fuelType, tankCapacity, fuelLevel, status) {
    // This would open an edit modal or redirect to edit page
    // For now, we'll just show an alert
    alert('Edit vehicle functionality would be implemented here');
}
</script>
@endpush
