@extends('layouts.app')

@section('title', 'New Fuel Request')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Submit New Fuel Request</h6>
                        <a href="{{ auth()->user()->isClient() ? route('client-portal.requests.index') : route('fuel-requests.index') }}"
                            class="btn btn-outline-secondary btn-sm">Back</a>
                    </div>
                    <div class="card-body">
                        <form method="POST"
                            action="{{ auth()->user()->isClient() ? route('client-portal.requests.store') : route('fuel-requests.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Vehicle *</label>
                                        <select name="vehicle_id"
                                            class="form-control @error('vehicle_id') is-invalid @enderror" required>
                                            <option value="">Select vehicle...</option>
                                            @foreach($vehicles ?? [] as $vehicle)
                                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                    {{ $vehicle->plate_number }} — {{ $vehicle->make }} {{ $vehicle->model }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vehicle_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Station *</label>
                                        <select name="station_id"
                                            class="form-control @error('station_id') is-invalid @enderror" required>
                                            <option value="">Select station...</option>
                                            @foreach($stations ?? [] as $station)
                                                <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                                                    {{ $station->name }} — {{ $station->city }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Fuel Type *</label>
                                        <select name="fuel_type"
                                            class="form-control @error('fuel_type') is-invalid @enderror" required>
                                            <option value="">Select fuel...</option>
                                            <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel
                                            </option>
                                            <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol
                                            </option>
                                        </select>
                                        @error('fuel_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Quantity (L) *</label>
                                        <input type="number" name="quantity_requested" step="0.01" min="0"
                                            value="{{ old('quantity_requested') }}"
                                            class="form-control @error('quantity_requested') is-invalid @enderror" required>
                                        @error('quantity_requested')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static mb-4">
                                        <label>Preferred Date *</label>
                                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                                            class="form-control @error('preferred_date') is-invalid @enderror" required>
                                        @error('preferred_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="input-group input-group-static mb-4">
                                <label>Special Instructions</label>
                                <textarea name="special_instructions" rows="3"
                                    class="form-control @error('special_instructions') is-invalid @enderror"
                                    placeholder="Any notes for the station...">{{ old('special_instructions') }}</textarea>
                                @error('special_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="text-sm mb-0">
                            <li>Ensure the vehicle is active and assigned to your company.</li>
                            <li>Pick a station with available stock and your preferred location.</li>
                            <li>Quantity and date affect approval and fulfillment.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection