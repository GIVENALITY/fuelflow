@extends('layouts.app')

@section('title', 'Create Fuel Order')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary p-3">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-white mb-0">
                                <i class="fas fa-plus-circle me-2"></i>Create Fuel Order
                            </h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Credit Info Banner -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <strong><i class="fas fa-credit-card me-2"></i>Credit Limit:</strong>
                                <span class="float-end">TZS {{ number_format($creditLimit, 0) }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-wallet me-2"></i>Current Balance:</strong>
                                <span class="float-end text-warning">TZS {{ number_format($currentBalance, 0) }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong><i class="fas fa-check-circle me-2"></i>Available Credit:</strong>
                                <span class="float-end text-success">TZS {{ number_format($availableCredit, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('client.orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Driver Name *</label>
                                    <input type="text" 
                                           class="form-control @error('driver_name') is-invalid @enderror" 
                                           name="driver_name" 
                                           value="{{ old('driver_name') }}" 
                                           placeholder="Enter driver's full name"
                                           required>
                                    @error('driver_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Select Vehicle (Truck Number) *</label>
                                    <select class="form-control @error('vehicle_id') is-invalid @enderror" 
                                            name="vehicle_id" 
                                            id="vehicle_select"
                                            required>
                                        <option value="">-- Select Truck --</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" 
                                                    data-fuel-type="{{ $vehicle->fuel_type }}"
                                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                {{ $vehicle->plate_number }} 
                                                @if($vehicle->truck_type)
                                                    ({{ ucfirst($vehicle->truck_type) }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Fuel Station *</label>
                                    <select class="form-control @error('station_id') is-invalid @enderror" 
                                            name="station_id" 
                                            required>
                                        <option value="">-- Select Station --</option>
                                        @foreach($stations as $station)
                                            <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                {{ $station->name }} - {{ $station->location }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('station_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Fuel Type *</label>
                                    <select class="form-control @error('fuel_type') is-invalid @enderror" 
                                            name="fuel_type" 
                                            id="fuel_type"
                                            required>
                                        <option value="">-- Select Fuel Type --</option>
                                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="gasoline" {{ old('fuel_type') == 'gasoline' ? 'selected' : '' }}>Gasoline (Petrol)</option>
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Amount of Litres *</label>
                                    <input type="number" 
                                           class="form-control @error('quantity_requested') is-invalid @enderror" 
                                           name="quantity_requested" 
                                           id="quantity_requested"
                                           value="{{ old('quantity_requested') }}" 
                                           min="1"
                                           step="0.1"
                                           placeholder="Enter litres (e.g., 500)"
                                           required>
                                    @error('quantity_requested')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Estimated cost: TZS <span id="estimated_cost">0</span></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Additional Notes (Optional)</label>
                                    <textarea class="form-control" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Any special instructions or notes">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning" id="credit_warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Notice:</strong> This order exceeds your available credit limit. 
                                    A ticket request will be submitted for approval.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Order
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vehicleSelect = document.getElementById('vehicle_select');
    const fuelTypeSelect = document.getElementById('fuel_type');
    const quantityInput = document.getElementById('quantity_requested');
    const estimatedCostSpan = document.getElementById('estimated_cost');
    const creditWarning = document.getElementById('credit_warning');
    
    const pricePerLiter = 3000; // Default price
    const availableCredit = {{ $availableCredit }};

    // Auto-select fuel type based on vehicle
    vehicleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const fuelType = selectedOption.getAttribute('data-fuel-type');
        if (fuelType) {
            fuelTypeSelect.value = fuelType;
        }
    });

    // Calculate estimated cost
    quantityInput.addEventListener('input', function() {
        const quantity = parseFloat(this.value) || 0;
        const estimatedCost = quantity * pricePerLiter;
        estimatedCostSpan.textContent = estimatedCost.toLocaleString();
        
        // Show warning if exceeds credit
        if (estimatedCost > availableCredit) {
            creditWarning.style.display = 'block';
        } else {
            creditWarning.style.display = 'none';
        }
    });
});
</script>
@endsection

