@extends('layouts.app')

@section('title', 'Add New Route')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Add New Route</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('routes.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Routes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('routes.store') }}" method="POST" id="routeForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Route Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Start Location *</label>
                                    <select class="form-control @error('start_location_id') is-invalid @enderror" name="start_location_id" required>
                                        <option value="">Select Start Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('start_location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }} ({{ ucfirst($location->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('start_location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>End Location *</label>
                                    <select class="form-control @error('end_location_id') is-invalid @enderror" name="end_location_id" required>
                                        <option value="">Select End Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('end_location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }} ({{ ucfirst($location->type) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('end_location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Total Distance (km)</label>
                                    <input type="number" class="form-control @error('total_distance') is-invalid @enderror" 
                                           name="total_distance" value="{{ old('total_distance') }}" min="0" step="0.1">
                                    @error('total_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Estimated Duration (minutes)</label>
                                    <input type="number" class="form-control @error('estimated_duration') is-invalid @enderror" 
                                           name="estimated_duration" value="{{ old('estimated_duration') }}" min="0">
                                    @error('estimated_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-group input-group-static mb-4">
                                    <label>Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Route
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Route Stops Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Route Stops (Optional)</h6>
                                        <p class="text-sm text-secondary mb-0">Add intermediate stops between start and end locations</p>
                                    </div>
                                    <div class="card-body">
                                        <div id="routeStops">
                                            <!-- Dynamic stops will be added here -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addStop()">
                                            <i class="material-symbols-rounded">add</i> Add Stop
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">save</i>Create Route
                                </button>
                                <a href="{{ route('routes.index') }}" class="btn btn-secondary">
                                    <i class="material-symbols-rounded me-2">cancel</i>Cancel
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
let stopCounter = 0;

function addStop() {
    const stopsContainer = document.getElementById('routeStops');
    const stopDiv = document.createElement('div');
    stopDiv.className = 'row mb-3 stop-row';
    stopDiv.innerHTML = `
        <div class="col-md-4">
            <div class="input-group input-group-static">
                <label>Location</label>
                <select class="form-control" name="stops[${stopCounter}][location_id]" required>
                    <option value="">Select Location</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }} ({{ ucfirst($location->type) }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-static">
                <label>Order</label>
                <input type="number" class="form-control" name="stops[${stopCounter}][order]" value="${stopCounter + 1}" min="1" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group input-group-static">
                <label>Time from Previous (min)</label>
                <input type="number" class="form-control" name="stops[${stopCounter}][estimated_time]" min="0">
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-outline-danger mt-4" onclick="removeStop(this)">
                <i class="material-symbols-rounded">delete</i>
            </button>
        </div>
    `;
    stopsContainer.appendChild(stopDiv);
    stopCounter++;
}

function removeStop(button) {
    button.closest('.stop-row').remove();
}
</script>
@endsection
