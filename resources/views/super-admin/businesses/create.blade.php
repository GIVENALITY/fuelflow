@extends('layouts.app')

@section('title', 'Add New Business')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0 font-weight-bold">Add New Fuel Station Business</h6>
                                <p class="text-sm text-muted mb-0">Create a new business account and admin user</p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('super-admin.businesses.index') }}" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Businesses
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <form action="{{ route('super-admin.businesses.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-bold mb-3">Business Information</h6>
                                    
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Business Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Registration Number</label>
                                        <input type="text" class="form-control" name="registration_number" value="{{ old('registration_number') }}">
                                    </div>

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Business Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                                    </div>
                                    @error('phone')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person') }}" required>
                                    </div>
                                    @error('contact_person')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <textarea class="form-control" name="address" rows="3" placeholder="Business Address" required>{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-sm font-weight-bold mb-3">Admin Account</h6>
                                    
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Admin Name</label>
                                        <input type="text" class="form-control" name="admin_name" value="{{ old('admin_name') }}" required>
                                    </div>
                                    @error('admin_name')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Admin Email</label>
                                        <input type="email" class="form-control" name="admin_email" value="{{ old('admin_email') }}" required>
                                    </div>
                                    @error('admin_email')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Admin Password</label>
                                        <input type="password" class="form-control" name="admin_password" required>
                                    </div>
                                    @error('admin_password')
                                        <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                    @enderror

                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="admin_password_confirmation" required>
                                    </div>

                                    <div class="input-group input-group-outline mb-3">
                                        <textarea class="form-control" name="notes" rows="3" placeholder="Additional Notes">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="text-center pt-3">
                                        <button type="submit" class="btn btn-dark px-4">
                                            <i class="fas fa-plus me-2"></i>Create Business & Admin Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
