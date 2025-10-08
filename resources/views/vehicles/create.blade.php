@extends('layouts.app')

@section('title', 'Add New Vehicle')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Vehicle Registration</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="fas fa-arrow-left"></i> Back to Vehicles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('vehicles.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Client *</label>
                                    <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" required>
                                        <option value="">Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->company_name }} - {{ $client->contact_person }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Vehicle Number (Plate Number) *</label>
                                    <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                           name="plate_number" value="{{ old('plate_number') }}" placeholder="e.g., T123 ABC" required>
                                    @error('plate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Kadi ya Kichwa (Head Card Number)</label>
                                    <input type="text" class="form-control @error('kadi_kichwa') is-invalid @enderror" 
                                           name="kadi_kichwa" value="{{ old('kadi_kichwa') }}" placeholder="Head registration card number">
                                    @error('kadi_kichwa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Kadi ya Trailer (Trailer Card Number)</label>
                                    <input type="text" class="form-control @error('kadi_trailer') is-invalid @enderror" 
                                           name="kadi_trailer" value="{{ old('kadi_trailer') }}" placeholder="Trailer registration card number">
                                    @error('kadi_trailer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Type of Truck *</label>
                                    <select class="form-control @error('truck_type') is-invalid @enderror" name="truck_type" required>
                                        <option value="">Select Truck Type</option>
                                        <option value="tractor" {{ old('truck_type') == 'tractor' ? 'selected' : '' }}>Tractor</option>
                                        <option value="trailer" {{ old('truck_type') == 'trailer' ? 'selected' : '' }}>Trailer</option>
                                        <option value="tractor_trailer" {{ old('truck_type') == 'tractor_trailer' ? 'selected' : '' }}>Tractor with Trailer</option>
                                    </select>
                                    @error('truck_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Fuel Type *</label>
                                    <select class="form-control @error('fuel_type') is-invalid @enderror" name="fuel_type" required>
                                        <option value="">Select Fuel Type</option>
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
                                    <label>Tank Capacity (Liters)</label>
                                    <input type="number" class="form-control @error('tank_capacity') is-invalid @enderror" 
                                           name="tank_capacity" value="{{ old('tank_capacity') }}" min="0" step="0.1" placeholder="e.g., 500">
                                    @error('tank_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
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
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Register Vehicle
                                </button>
                                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
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
@endsection
