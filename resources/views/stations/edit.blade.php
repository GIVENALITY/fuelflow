@extends('layouts.app')

@section('title', 'Edit Station')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Edit Station</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('stations.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Stations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('stations.update', $station) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Station Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $station->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Station Code *</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           name="code" value="{{ old('code', $station->code) }}" required placeholder="e.g., STN001">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Location *</label>
                                    <select class="form-control @error('location_id') is-invalid @enderror" name="location_id" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id', $station->location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }} - {{ $location->full_address }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
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
                                        <option value="active" {{ old('status', $station->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $station->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($station->manager)
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="material-symbols-rounded me-2">info</i>
                                    <strong>Current Manager:</strong> {{ $station->manager->name }} ({{ $station->manager->email }})
                                    <br><small>To change the manager, use the Station Managers section.</small>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="material-symbols-rounded me-2">warning</i>
                                    <strong>No Manager Assigned:</strong> This station doesn't have a manager assigned.
                                    <br><small>Assign a manager through the Station Managers section.</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="material-symbols-rounded me-2">save</i>Update Station
                                </button>
                                <a href="{{ route('stations.index') }}" class="btn btn-secondary">
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
