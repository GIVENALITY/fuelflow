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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
