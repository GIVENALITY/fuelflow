@extends('layouts.app')

@section('title', 'Edit Location')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Edit Location</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('locations.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Locations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('locations.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Location Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $location->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Type *</label>
                                    <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="depot" {{ old('type', $location->type) == 'depot' ? 'selected' : '' }}>Depot</option>
                                        <option value="station" {{ old('type', $location->type) == 'station' ? 'selected' : '' }}>Station</option>
                                        <option value="client_location" {{ old('type', $location->type) == 'client_location' ? 'selected' : '' }}>Client Location</option>
                                        <option value="other" {{ old('type', $location->type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Contact Person</label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                           name="contact_person" value="{{ old('contact_person', $location->contact_person) }}">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone', $location->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $location->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $location->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $location->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="input-group input-group-static mb-4">
                                    <label>Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              name="address" rows="3">{{ old('address', $location->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-static mb-4">
                                    <label>City</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                           name="city" value="{{ old('city', $location->city) }}">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-static mb-4">
                                    <label>State/Region</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                           name="state" value="{{ old('state', $location->state) }}">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-static mb-4">
                                    <label>Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                           name="country" value="{{ old('country', $location->country) }}">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Latitude</label>
                                    <input type="number" class="form-control @error('latitude') is-invalid @enderror" 
                                           name="latitude" value="{{ old('latitude', $location->latitude) }}" step="0.000001" min="-90" max="90">
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Longitude</label>
                                    <input type="number" class="form-control @error('longitude') is-invalid @enderror" 
                                           name="longitude" value="{{ old('longitude', $location->longitude) }}" step="0.000001" min="-180" max="180">
                                    @error('longitude')
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
                                              name="description" rows="3">{{ old('description', $location->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">save</i>Update Location
                                </button>
                                <a href="{{ route('locations.index') }}" class="btn btn-secondary">
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
