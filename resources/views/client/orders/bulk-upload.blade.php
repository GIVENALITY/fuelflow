@extends('layouts.app')

@section('title', 'Bulk Order Upload')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-success p-3">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-white mb-0">
                                <i class="fas fa-upload me-2"></i>Bulk Order Upload
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Quick Bulk Upload:</strong> Add multiple fuel orders at once using the form below. 
                        Click "Add Another Order" to add more rows.
                    </div>

                    <form action="{{ route('client.orders.store-bulk') }}" method="POST" id="bulkOrderForm">
                        @csrf

                        <div id="orders-container">
                            <!-- Order Row Template -->
                            <div class="order-row card mb-3" data-row="0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <div class="input-group input-group-static mb-2">
                                                <label class="small">Driver Name *</label>
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       name="orders[0][driver_name]" 
                                                       placeholder="Driver name"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group input-group-static mb-2">
                                                <label class="small">Vehicle *</label>
                                                <select class="form-control form-control-sm" 
                                                        name="orders[0][vehicle_id]" 
                                                        required>
                                                    <option value="">Select Truck</option>
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}">
                                                            {{ $vehicle->plate_number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group input-group-static mb-2">
                                                <label class="small">Station *</label>
                                                <select class="form-control form-control-sm" 
                                                        name="orders[0][station_id]" 
                                                        required>
                                                    <option value="">Select Station</option>
                                                    @foreach($stations as $station)
                                                        <option value="{{ $station->id }}">
                                                            {{ $station->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="input-group input-group-static mb-2">
                                                <label class="small">Fuel Type *</label>
                                                <select class="form-control form-control-sm" 
                                                        name="orders[0][fuel_type]" 
                                                        required>
                                                    <option value="">Select</option>
                                                    <option value="diesel">Diesel</option>
                                                    <option value="gasoline">Gasoline</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-1">
                                            <div class="input-group input-group-static mb-2">
                                                <label class="small">Litres *</label>
                                                <input type="number" 
                                                       class="form-control form-control-sm" 
                                                       name="orders[0][quantity_requested]" 
                                                       placeholder="Litres"
                                                       min="1"
                                                       step="0.1"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row" style="margin-top: 20px;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <button type="button" class="btn btn-outline-primary" id="addRowBtn">
                                    <i class="fas fa-plus-circle me-2"></i>Add Another Order
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check me-2"></i>Submit All Orders
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
    let rowCount = 1;
    const container = document.getElementById('orders-container');
    const addRowBtn = document.getElementById('addRowBtn');

    // Vehicle and station data for template
    const vehicles = @json($vehicles);
    const stations = @json($stations);

    addRowBtn.addEventListener('click', function() {
        const newRow = document.querySelector('.order-row').cloneNode(true);
        newRow.setAttribute('data-row', rowCount);
        
        // Update all input names with new index
        newRow.querySelectorAll('input, select').forEach(function(input) {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('[0]', `[${rowCount}]`));
                input.value = '';
            }
        });
        
        container.appendChild(newRow);
        rowCount++;
    });

    // Remove row functionality
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const rows = container.querySelectorAll('.order-row');
            if (rows.length > 1) {
                e.target.closest('.order-row').remove();
            } else {
                alert('You must have at least one order row.');
            }
        }
    });
});
</script>
@endsection

