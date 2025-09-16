@extends('layouts.app')

@section('title', 'User Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">User Details</h6>
                                <p class="text-sm text-secondary mb-0">View user information and activity</p>
                            </div>
                            <div>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="material-symbols-rounded me-1">arrow_back</i>
                                    Back to Users
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                                        <i class="material-symbols-rounded me-1">edit</i>
                                        Edit User
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- User Information -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>User Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Full Name</label>
                                            <p class="form-control-plaintext">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email Address</label>
                                            <p class="form-control-plaintext">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <p class="form-control-plaintext">{{ $user->phone ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Role</label>
                                            <div>
                                                @if($user->isAdmin())
                                                    <span class="badge bg-primary">Admin</span>
                                                @elseif($user->isStationManager())
                                                    <span class="badge bg-info">Station Manager</span>
                                                @elseif($user->isFuelPumper())
                                                    <span class="badge bg-warning">Fuel Pumper</span>
                                                @elseif($user->isTreasury())
                                                    <span class="badge bg-success">Treasury</span>
                                                @elseif($user->isClient())
                                                    <span class="badge bg-secondary">Client</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ ucfirst($user->role) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($user->station)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Assigned Station</label>
                                                <p class="form-control-plaintext">{{ $user->station->name }} -
                                                    {{ $user->station->location }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Address</label>
                                            <p class="form-control-plaintext">{{ $user->address ?? 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <div>
                                                @if($user->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Member Since</label>
                                            <p class="form-control-plaintext">{{ $user->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Profile Photo -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Profile Photo</h6>
                            </div>
                            <div class="card-body text-center">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo"
                                        class="img-fluid rounded-circle"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 150px; height: 150px;">
                                        <i class="material-symbols-rounded text-muted" style="font-size: 4rem;">person</i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        @if(auth()->user()->isAdmin())
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="material-symbols-rounded me-1">edit</i>
                                            Edit User
                                        </a>

                                        @if($user->is_active)
                                            <form method="POST" action="{{ route('users.deactivate', $user) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-warning btn-sm w-100"
                                                    onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                    <i class="material-symbols-rounded me-1">block</i>
                                                    Deactivate User
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('users.activate', $user) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                                    <i class="material-symbols-rounded me-1">check_circle</i>
                                                    Activate User
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection