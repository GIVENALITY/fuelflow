@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Create New User</h6>
                                <p class="text-sm text-secondary mb-0">Add a new user to the system</p>
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
                        <!-- User Creation Form -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>User Information</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Full Name *</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    name="name" value="{{ old('name') }}" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Email Address *</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Phone Number</label>
                                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                    name="phone" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Role *</label>
                                                <select class="form-control @error('role') is-invalid @enderror" name="role"
                                                    required>
                                                    <option value="">Select Role</option>
                                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                                        Admin</option>
                                                    <option value="station_manager" {{ old('role') === 'station_manager' ? 'selected' : '' }}>Station Manager</option>
                                                    <option value="fuel_pumper" {{ old('role') === 'fuel_pumper' ? 'selected' : '' }}>Fuel Pumper</option>
                                                    <option value="treasury" {{ old('role') === 'treasury' ? 'selected' : '' }}>Treasury</option>
                                                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>
                                                        Client</option>
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
                                                <label class="form-label">Password *</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Confirm Password *</label>
                                                <input type="password"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    name="password_confirmation" required>
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
                                                <div class="input-group input-group-outline mb-4">
                                                    <label class="form-label">Assigned Station</label>
                                                    <select class="form-control @error('station_id') is-invalid @enderror"
                                                        name="station_id">
                                                        <option value="">Select Station</option>
                                                        @foreach($stations as $station)
                                                            <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
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
                                            <div class="input-group input-group-outline mb-4">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control @error('address') is-invalid @enderror"
                                                    name="address" rows="3">{{ old('address') }}</textarea>
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
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                                <label class="form-check-label">Active User</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('users.index') }}"
                                            class="btn btn-outline-secondary me-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-symbols-rounded me-1">save</i>
                                            Create User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Role Information -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Role Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="role-info">
                                    <div class="role-item mb-3">
                                        <h6 class="text-sm font-weight-bold text-primary">Admin</h6>
                                        <p class="text-xs text-secondary mb-0">Full system access, user management, system
                                            configuration</p>
                                    </div>
                                    <div class="role-item mb-3">
                                        <h6 class="text-sm font-weight-bold text-info">Station Manager</h6>
                                        <p class="text-xs text-secondary mb-0">Manage station operations, approve requests,
                                            oversee pumpers</p>
                                    </div>
                                    <div class="role-item mb-3">
                                        <h6 class="text-sm font-weight-bold text-warning">Fuel Pumper</h6>
                                        <p class="text-xs text-secondary mb-0">Dispense fuel, complete assigned requests,
                                            upload receipts</p>
                                    </div>
                                    <div class="role-item mb-3">
                                        <h6 class="text-sm font-weight-bold text-success">Treasury</h6>
                                        <p class="text-xs text-secondary mb-0">Verify receipts, manage payments, financial
                                            oversight</p>
                                    </div>
                                    <div class="role-item">
                                        <h6 class="text-sm font-weight-bold text-secondary">Client</h6>
                                        <p class="text-xs text-secondary mb-0">Request fuel, view reports, manage vehicles
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Help</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-sm text-secondary mb-2">
                                    <strong>Required Fields:</strong> All fields marked with * are required.
                                </p>
                                <p class="text-sm text-secondary mb-2">
                                    <strong>Station Assignment:</strong> Required for Station Managers and Fuel Pumpers.
                                </p>
                                <p class="text-sm text-secondary mb-0">
                                    <strong>Password:</strong> Must be at least 8 characters long.
                                </p>
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
                if (selectedRole === 'station_manager' || selectedRole === 'fuel_pumper') {
                    stationAssignment.style.display = 'block';
                    stationSelect.required = true;
                } else {
                    stationAssignment.style.display = 'none';
                    stationSelect.required = false;
                    stationSelect.value = '';
                }
            }

            // Initial check
            toggleStationAssignment();

            // Listen for role changes
            roleSelect.addEventListener('change', toggleStationAssignment);

            // Handle form field focus and blur for proper label behavior
            const formInputs = document.querySelectorAll('.form-control');
            formInputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.parentElement.classList.add('is-focused');
                });

                input.addEventListener('blur', function () {
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