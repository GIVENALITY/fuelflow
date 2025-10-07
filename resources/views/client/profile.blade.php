@extends('layouts.app')

@section('title', 'Complete Your Profile')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Complete Your Company Profile</h6>
                            <p class="text-sm text-secondary mb-0">Add your company details and required documents</p>
                        </div>
                        <div>
                            <span class="badge badge-sm {{ auth()->user()->client->registration_status === 'active' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst(auth()->user()->client->registration_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Completion Form -->
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Company Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="fas fa-building me-2"></i>Company Details</h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contact_person" class="form-label">Contact Person *</label>
                                <input type="text" class="form-control" id="contact_person" name="contact_person" 
                                       value="{{ old('contact_person', auth()->user()->client->contact_person) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ old('phone', auth()->user()->client->phone) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="address" class="form-label">Street Address *</label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="{{ old('address', auth()->user()->client->address) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="{{ old('city', auth()->user()->client->city) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="state" class="form-label">State/Region *</label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="{{ old('state', auth()->user()->client->state) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="zip_code" class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                       value="{{ old('zip_code', auth()->user()->client->zip_code) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label">Country *</label>
                                <input type="text" class="form-control" id="country" name="country" 
                                       value="{{ old('country', auth()->user()->client->country) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tax_id" class="form-label">Tax ID *</label>
                                <input type="text" class="form-control" id="tax_id" name="tax_id" 
                                       value="{{ old('tax_id', auth()->user()->client->tax_id) }}" required>
                            </div>
                        </div>

                        <!-- Document Uploads -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Required Documents</h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tin_document" class="form-label">TIN Document *</label>
                                <input type="file" class="form-control" id="tin_document" name="tin_document" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                @if(auth()->user()->client->tin_document_path)
                                    <div class="mt-1">
                                        <small class="text-success"><i class="fas fa-check me-1"></i>Document uploaded</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="brela_certificate" class="form-label">BRELA Certificate *</label>
                                <input type="file" class="form-control" id="brela_certificate" name="brela_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                @if(auth()->user()->client->brela_certificate_path)
                                    <div class="mt-1">
                                        <small class="text-success"><i class="fas fa-check me-1"></i>Document uploaded</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="business_license" class="form-label">Business License *</label>
                                <input type="file" class="form-control" id="business_license" name="business_license" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                @if(auth()->user()->client->business_license_path)
                                    <div class="mt-1">
                                        <small class="text-success"><i class="fas fa-check me-1"></i>Document uploaded</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="director_id" class="form-label">Director ID *</label>
                                <input type="file" class="form-control" id="director_id" name="director_id" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                @if(auth()->user()->client->director_id_path)
                                    <div class="mt-1">
                                        <small class="text-success"><i class="fas fa-check me-1"></i>Document uploaded</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
