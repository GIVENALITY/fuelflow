@extends('layouts.app')

@section('title', 'Add New Business')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="text-white text-capitalize ps-3">
                                        <i class="fas fa-plus me-2"></i>Add New Fuel Station Business
                                    </h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('super-admin.businesses.index') }}" class="btn btn-sm btn-light me-3">
                                        <i class="material-symbols-rounded">arrow_back</i> Back to Businesses
                                    </a>
                                </div>
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
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-primary">
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
