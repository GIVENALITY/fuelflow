@extends('layouts.app')

@section('title', 'Business Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0 font-weight-bold">{{ $business->name }}</h6>
                                <p class="text-sm text-muted mb-0">Business Details & Management</p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('super-admin.businesses.index') }}" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Businesses
                                </a>
                                <a href="{{ route('super-admin.businesses.edit', $business) }}" class="btn btn-sm btn-dark ms-2">
                                    <i class="fas fa-edit me-2"></i>Edit Business
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold mb-3">Business Information</h6>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Business Name</label>
                                    <p class="mb-0 font-weight-bold">{{ $business->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Business Code</label>
                                    <p class="mb-0 font-weight-bold">{{ $business->business_code }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Email</label>
                                    <p class="mb-0">{{ $business->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Phone</label>
                                    <p class="mb-0">{{ $business->phone }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Contact Person</label>
                                    <p class="mb-0">{{ $business->contact_person }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="font-weight-bold mb-3">Status & Statistics</h6>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Status</label>
                                    <div>
                                        <span class="badge badge-sm bg-gradient-{{ $business->status === 'approved' ? 'success' : ($business->status === 'pending' ? 'warning' : ($business->status === 'suspended' ? 'danger' : 'secondary')) }}">
                                            {{ $business->status_display_name }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Registration Status</label>
                                    <div>
                                        <span class="badge badge-sm bg-gradient-{{ $business->registration_status === 'approved' ? 'success' : ($business->registration_status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $business->registration_status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Contract Status</label>
                                    <div>
                                        @if($business->contract_signed)
                                            <span class="badge badge-sm bg-gradient-success">
                                                <i class="fas fa-check me-1"></i>Signed
                                            </span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-sm text-muted">Approved At</label>
                                    <p class="mb-0">{{ $business->approved_at ? $business->approved_at->format('M d, Y H:i') : 'Not approved' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($business->address)
                        <div class="row">
                            <div class="col-12">
                                <h6 class="font-weight-bold mb-3">Address</h6>
                                <p class="mb-0">{{ $business->address }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($business->notes)
                        <div class="row">
                            <div class="col-12">
                                <h6 class="font-weight-bold mb-3">Notes</h6>
                                <p class="mb-0">{{ $business->notes }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Contract Management Section -->
                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <h6 class="font-weight-bold mb-3">Contract Management</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <h6 class="font-weight-bold mb-3">Upload Contract</h6>
                                                @if($business->contract_signed)
                                                    <div class="alert alert-success mb-3">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        Contract has been uploaded and signed
                                                        @if($business->contract_uploaded_at)
                                                            <br><small>Uploaded: {{ $business->contract_uploaded_at->format('M d, Y H:i') }}</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <form action="{{ route('super-admin.businesses.upload-contract', $business) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="contract_file" class="form-label">Select Contract File (PDF)</label>
                                                            <input type="file" class="form-control" id="contract_file" name="contract_file" accept=".pdf" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-dark btn-sm">
                                                            <i class="fas fa-upload me-2"></i>Upload Contract
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body p-3">
                                                <h6 class="font-weight-bold mb-3">Business Actions</h6>
                                                
                                                @if($business->status === 'pending')
                                                    <form action="{{ route('super-admin.businesses.approve', $business) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                                            <i class="fas fa-check me-2"></i>Approve Business
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($business->status === 'approved')
                                                    <form action="{{ route('super-admin.businesses.suspend', $business) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm w-100">
                                                            <i class="fas fa-pause me-2"></i>Suspend Business
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($business->status === 'suspended')
                                                    <form action="{{ route('super-admin.businesses.activate', $business) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                                            <i class="fas fa-play me-2"></i>Activate Business
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <strong>Status Flow:</strong><br>
                                                        Pending → Approved → Active<br>
                                                        Can be suspended at any time
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
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
