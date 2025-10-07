@extends('layouts.app')

@section('title', 'My Vehicles')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">My Vehicles</h6>
                            <p class="text-sm text-secondary mb-0">Manage your registered vehicles</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                            <i class="fas fa-plus me-2"></i>Add Vehicle
                        </button>
                    </div>
                </div>
            </div>

            <!-- Vehicles List -->
            <div class="card">
                <div class="card-body p-0">
                    @if($vehicles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel Type</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehicles as $vehicle)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-truck text-primary" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $vehicle->plate_number }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm">{{ ucfirst($vehicle->vehicle_type) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-sm">{{ ucfirst($vehicle->fuel_type) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm {{ $vehicle->status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($vehicle->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                                            onclick="editVehicle({{ $vehicle->id }})" title="Edit Vehicle">
                                                        <i class="fas fa-edit"></i>
                                                        <span class="d-none d-md-inline ms-1">Edit</span>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="deleteVehicle({{ $vehicle->id }})" title="Delete Vehicle">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="d-none d-md-inline ms-1">Delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-truck text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">No vehicles registered</h5>
                            <p class="text-muted">Add your first vehicle to start using FuelFlow services.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                                <i class="fas fa-plus me-2"></i>Add Your First Vehicle
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">Add New Vehicle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('client.vehicles.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label>Vehicle Type *</label>
                                <select class="form-control" name="vehicle_type" required>
                                    <option value="">Select Type</option>
                                    <option value="truck">Truck</option>
                                    <option value="tractor">Tractor</option>
                                    <option value="trailer">Trailer</option>
                                    <option value="van">Van</option>
                                    <option value="car">Car</option>
                                    <option value="bus">Bus</option>
                                    <option value="motorcycle">Motorcycle</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group input-group-static mb-4">
                                <label>Make *</label>
                                <input type="text" class="form-control" name="make" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-static mb-4">
                                <label>Model *</label>
                                <input type="text" class="form-control" name="model" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-static mb-4">
                                <label>Year *</label>
                                <input type="number" class="form-control" name="year" min="1900" max="{{ date('Y') }}" value="{{ date('Y') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Fuel Type *</label>
                                <select class="form-control" name="fuel_type" required>
                                    <option value="">Select Fuel Type</option>
                                    <option value="diesel">Diesel</option>
                                    <option value="petrol">Gasoline</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Head Card (Kadi ya Kichwa) *</label>
                                <input type="file" class="form-control" name="head_card" accept=".pdf,.jpg,.jpeg,.png" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Trailer Card (Kadi ya Trailer)</label>
                                <input type="file" class="form-control" name="trailer_card" accept=".pdf,.jpg,.jpeg,.png">
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

<script>
function editVehicle(vehicleId) {
    // TODO: Implement edit vehicle functionality
    alert('Edit vehicle functionality coming soon!');
}

function deleteVehicle(vehicleId) {
    if (confirm('Are you sure you want to delete this vehicle?')) {
        // TODO: Implement delete vehicle functionality
        alert('Delete vehicle functionality coming soon!');
    }
}
</script>
@endsection
