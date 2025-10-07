@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Edit User</h6>
                                <p class="text-sm text-secondary mb-0">Update user information</p>
                            </div>
                            <div>
                                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="material-symbols-rounded me-1">arrow_back</i>
                                    Back to User
                                </a>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="material-symbols-rounded me-1">list</i>
                                    All Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- User Edit Form -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>User Information</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('users.update', $user) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline {{ old('name', $user->name) ? 'is-filled' : '' }} mb-4">
                                                <label class="form-label">Full Name *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline {{ old('email', $user->email) ? 'is-filled' : '' }} mb-4">
                                                <label class="form-label">Email Address *</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline {{ old('phone', $user->phone) ? 'is-filled' : '' }} mb-4">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                    name="phone" value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline {{ old('role', $user->role) ? 'is-filled' : '' }} mb-4">
                                                <label class="form-label">Role *</label>
                                                <select class="form-control @error('role') is-invalid @enderror" name="role"
                                                    required>
                                                    <option value="">Select Role</option>
                                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="station_manager" {{ old('role', $user->role) === 'station_manager' ? 'selected' : '' }}>Station
                                                        Manager</option>
                                                    <option value="station_attendant" {{ old('role', $user->role) === 'station_attendant' ? 'selected' : '' }}>Station Attendant</option>
                                                    <option value="treasury" {{ old('role', $user->role) === 'treasury' ? 'selected' : '' }}>Treasury</option>
                                                    <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Client</option>
                                                </select>
                                                @error('role')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">New Password</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" placeholder="Leave blank to keep current password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Confirm New Password</label>
                                                <input type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    name="password_confirmation"
                                                    placeholder="Leave blank to keep current password">
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Station Assignment (for Station Managers and Fuel Pumpers) -->
                                    <div id="station-assignment" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group input-group-static mb-4">
                                                    <label class="form-label">Assigned Station</label>
                                                    <select class="form-control @error('station_id') is-invalid @enderror"
                                                        name="station_id">
                                                        <option value="">Select Station</option>
                                                        @foreach($stations as $station)
                                                            <option value="{{ $station->id }}" {{ old('station_id', $user->station_id) == $station->id ? 'selected' : '' }}>
                                                                {{ $station->name }} - {{ $station->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('station_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group input-group-outline {{ old('address', $user->address) ? 'is-filled' : '' }} mb-4">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror"
                                                    name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Profile Photo</label>
                                                <input type="file"
                                                    class="form-control @error('profile_photo') is-invalid @enderror"
                                                    name="profile_photo" accept="image/*">
                                                @error('profile_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($user->profile_photo)
                                                    <small class="text-muted">Current:
                                                        {{ basename($user->profile_photo) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label">Active User</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('users.show', $user) }}"
                                            class="btn btn-outline-secondary me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-symbols-rounded me-1">save</i>
                                            Update User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Current Profile Photo -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Current Profile Photo</h6>
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

                        <!-- User Statistics -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>User Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="stat-item mb-3">
                                    <h6 class="text-sm font-weight-bold text-primary">Member Since</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="stat-item mb-3">
                                    <h6 class="text-sm font-weight-bold text-info">Last Updated</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="stat-item">
                                    <h6 class="text-sm font-weight-bold text-success">Status</h6>
                                    <p class="text-xs text-secondary mb-0">
                                        @if($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.querySelector('select[name="role"]');
            const stationAssignment = document.getElementById('station-assignment');
            const stationSelect = document.querySelector('select[name="station_id"]');

            function toggleStationAssignment() {
                const selectedRole = roleSelect.value;
                if (selectedRole === 'station_manager' || selectedRole === 'station_attendant') {
                    stationAssignment.style.display = 'block';
                    stationSelect.required = true;
                } else {
                    stationAssignment.style.display = 'none';
                    stationSelect.required = false;
                    if (selectedRole !== 'station_manager' && selectedRole !== 'station_attendant') {
                        stationSelect.value = '';
                    }
                }
            }

            // Initial check
            toggleStationAssignment();

    // Listen for role changes
    roleSelect.addEventListener('change', toggleStationAssignment);

    // Handle form field focus and blur for proper label behavior
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('is-focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('is-focused');
            if (this.value) {
                this.parentElement.classList.add('is-filled');
            } else {
                this.parentElement.classList.remove('is-filled');
            }
        });
        
        // Check if field has value on page load
        if (input.value) {
            input.parentElement.classList.add('is-filled');
        }
    });
});
</script>
@endsection