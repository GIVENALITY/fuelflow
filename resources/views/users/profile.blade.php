@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">User Profile</h6>
                                <p class="text-sm text-secondary mb-0">View and manage user profile information</p>
                            </div>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-1">arrow_back</i>
                                Back to Users
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Profile Information -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Profile Information</h6>
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

                        <!-- Recent Activity -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Recent Activity</h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-primary"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Account Created</h6>
                                            <p class="timeline-text">User account was created</p>
                                            <small class="text-muted">{{ $user->created_at->format('M d, Y H:i') }}</small>
                                        </div>
                                    </div>

                                    @if($user->updated_at != $user->created_at)
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Profile Updated</h6>
                                                <p class="timeline-text">User profile was last updated</p>
                                                <small class="text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</small>
                                            </div>
                                        </div>
                                    @endif
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
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="material-symbols-rounded me-1">edit</i>
                                        Edit Profile
                                    </a>

                                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info btn-sm">
                                        <i class="material-symbols-rounded me-1">visibility</i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Statistics -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Account Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="stat-item mb-3">
                                    <h6 class="text-sm font-weight-bold text-primary">Account Status</h6>
                                    <p class="text-xs text-secondary mb-0">
                                        @if($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="stat-item mb-3">
                                    <h6 class="text-sm font-weight-bold text-info">Member Since</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="stat-item">
                                    <h6 class="text-sm font-weight-bold text-success">Last Updated</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -35px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #e9ecef;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #344767;
        }

        .timeline-text {
            font-size: 13px;
            color: #67748e;
            margin-bottom: 5px;
        }

        .stat-item {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .stat-item:last-child {
            border-bottom: none;
        }
    </style>
@endsection