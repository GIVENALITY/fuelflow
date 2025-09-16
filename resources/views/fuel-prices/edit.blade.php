@extends('layouts.app')

@section('title', 'Edit Fuel Price')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="text-white text-capitalize ps-3">Edit Fuel Price</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('fuel-prices.index') }}" class="btn btn-sm btn-light me-3">
                                        <i class="material-symbols-rounded">arrow_back</i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form action="{{ route('fuel-prices.update', $fuelPrice) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div
                                        class="input-group input-group-outline {{ old('station_id', $fuelPrice->station_id) ? 'is-filled' : '' }} my-3">
                                        <label class="form-label">Station</label>
                                        <select name="station_id"
                                            class="form-control @error('station_id') is-invalid @enderror" required>
                                            <option value="">Select Station</option>
                                            @foreach($stations as $station)
                                                <option value="{{ $station->id }}" {{ old('station_id', $fuelPrice->station_id) == $station->id ? 'selected' : '' }}>
                                                    {{ $station->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('station_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div
                                        class="input-group input-group-outline {{ old('fuel_type', $fuelPrice->fuel_type) ? 'is-filled' : '' }} my-3">
                                        <label class="form-label">Fuel Type</label>
                                        <select name="fuel_type"
                                            class="form-control @error('fuel_type') is-invalid @enderror" required>
                                            <option value="">Select Fuel Type</option>
                                            <option value="petrol" {{ old('fuel_type', $fuelPrice->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                            <option value="diesel" {{ old('fuel_type', $fuelPrice->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        </select>
                                        @error('fuel_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div
                                        class="input-group input-group-outline {{ old('price', $fuelPrice->price) ? 'is-filled' : '' }} my-3">
                                        <label class="form-label">Price (TZS)</label>
                                        <input type="number" name="price"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price', $fuelPrice->price) }}" step="0.01" min="0" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div
                                        class="input-group input-group-outline {{ old('effective_date', $fuelPrice->effective_date->format('Y-m-d')) ? 'is-filled' : '' }} my-3">
                                        <label class="form-label">Effective Date</label>
                                        <input type="date" name="effective_date"
                                            class="form-control @error('effective_date') is-invalid @enderror"
                                            value="{{ old('effective_date', $fuelPrice->effective_date->format('Y-m-d')) }}"
                                            required>
                                        @error('effective_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div
                                        class="input-group input-group-outline {{ old('notes', $fuelPrice->notes) ? 'is-filled' : '' }} my-3">
                                        <label class="form-label">Notes (Optional)</label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                            rows="3">{{ old('notes', $fuelPrice->notes) }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-symbols-rounded">save</i> Update Fuel Price
                                    </button>
                                    <a href="{{ route('fuel-prices.index') }}" class="btn btn-secondary">
                                        <i class="material-symbols-rounded">cancel</i> Cancel
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