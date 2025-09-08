@extends('layouts.app')

@section('title', 'Station Manager Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Station Manager Details</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('station-managers.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Managers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Manager Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Full Name</label>
                                                <p class="form-control-plaintext">{{ $stationManager->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email Address</label>
                                                <p class="form-control-plaintext">{{ $stationManager->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <p class="form-control-plaintext">{{ $stationManager->phone ?? 'Not provided' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Status</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge {{ $stationManager->status === 'active' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                        {{ ucfirst($stationManager->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Role</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge bg-gradient-info">Station Manager</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Created At</label>
                                                <p class="form-control-plaintext">{{ $stationManager->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Station Assignment</h6>
                                </div>
                                <div class="card-body">
                                    @if($stationManager->station)
                                        <div class="text-center">
                                            <i class="material-symbols-rounded text-success" style="font-size: 48px;">location_on</i>
                                            <h6 class="mt-2">{{ $stationManager->station->name }}</h6>
                                            <p class="text-sm text-secondary mb-2">{{ $stationManager->station->code }}</p>
                                            <p class="text-xs text-secondary">{{ $stationManager->station->full_address }}</p>
                                            <div class="mt-3">
                                                <span class="badge {{ $stationManager->station->status === 'active' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                    {{ ucfirst($stationManager->station->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <i class="material-symbols-rounded text-secondary" style="font-size: 48px;">location_off</i>
                                            <h6 class="mt-2 text-secondary">No Station Assigned</h6>
                                            <p class="text-sm text-secondary">This manager is not assigned to any station.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($stationManager->station)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Station Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Station Name</label>
                                                <p class="form-control-plaintext">{{ $stationManager->station->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Station Code</label>
                                                <p class="form-control-plaintext">{{ $stationManager->station->code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Location</label>
                                                <p class="form-control-plaintext">{{ $stationManager->station->full_address }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Station Status</label>
                                                <p class="form-control-plaintext">
                                                    <span class="badge {{ $stationManager->station->status === 'active' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                        {{ ucfirst($stationManager->station->status) }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('station-managers.edit', $stationManager) }}" class="btn btn-primary">
                                        <i class="material-symbols-rounded me-2">edit</i>Edit Manager
                                    </a>
                                    @if($stationManager->station)
                                        <form action="{{ route('station-managers.unassign', $stationManager) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to unassign this manager from their station?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">
                                                <i class="material-symbols-rounded me-2">person_remove</i>Unassign Station
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div>
                                    <form action="{{ route('station-managers.destroy', $stationManager) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this station manager?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger"
                                                {{ $stationManager->station ? 'disabled' : '' }}>
                                            <i class="material-symbols-rounded me-2">delete</i>Delete Manager
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
