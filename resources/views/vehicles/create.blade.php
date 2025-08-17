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
                                <h6 class="text-white text-capitalize ps-3">Add New Vehicle</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Vehicles
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
                                    <label>Plate Number *</label>
                                    <input type="text" class="form-control @error('plate_number') is-invalid @enderror" 
                                           name="plate_number" value="{{ old('plate_number') }}" required>
                                    @error('plate_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Make *</label>
                                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                                           name="make" value="{{ old('make') }}" required>
                                    @error('make')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Model *</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           name="model" value="{{ old('model') }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Year *</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Fuel Type *</label>
                                    <select class="form-control @error('fuel_type') is-invalid @enderror" name="fuel_type" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
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
                                    <label>Tank Capacity (Liters) *</label>
                                    <input type="number" class="form-control @error('tank_capacity') is-invalid @enderror" 
                                           name="tank_capacity" value="{{ old('tank_capacity') }}" min="0" step="0.1" required>
                                    @error('tank_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Current Fuel Level (%) *</label>
                                    <input type="number" class="form-control @error('current_fuel_level') is-invalid @enderror" 
                                           name="current_fuel_level" value="{{ old('current_fuel_level') }}" min="0" max="100" step="1" required>
                                    @error('current_fuel_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
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
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">save</i>Create Vehicle
                                </button>
                                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
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
@endsection
