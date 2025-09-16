@extends('layouts.app')

@section('title', 'Receipt Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">
                                    @if(auth()->user()->isClient())
                                        My Receipt Details
                                    @elseif(auth()->user()->isStationManager())
                                        Station Receipt Details
                                    @elseif(auth()->user()->isFuelPumper())
                                        Assignment Receipt Details
                                    @elseif(auth()->user()->isTreasury())
                                        Receipt Verification Details
                                    @elseif(auth()->user()->isAdmin())
                                        System Receipt Details
                                    @else
                                        Receipt Details
                                    @endif
                                </h6>
                                <p class="text-sm text-secondary mb-0">
                                    @if(auth()->user()->isClient())
                                        View details of your fuel receipt
                                    @elseif(auth()->user()->isStationManager())
                                        View details of station receipt
                                    @elseif(auth()->user()->isFuelPumper())
                                        View details of your assigned receipt
                                    @elseif(auth()->user()->isTreasury())
                                        Review receipt for verification
                                    @elseif(auth()->user()->isAdmin())
                                        Monitor receipt details
                                    @else
                                        View receipt details
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-1">arrow_back</i>
                                Back to Receipts
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Receipt Information -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Receipt Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Receipt ID</label>
                                            <p class="form-control-plaintext">{{ $receipt->id }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <div>
                                                @if($receipt->status === 'pending')
                                                    <span class="badge bg-warning">Pending Verification</span>
                                                @elseif($receipt->status === 'verified')
                                                    <span class="badge bg-success">Verified</span>
                                                @elseif($receipt->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Amount</label>
                                            <p class="form-control-plaintext">${{ number_format($receipt->amount, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Quantity (Liters)</label>
                                            <p class="form-control-plaintext">{{ number_format($receipt->quantity, 2) }}L
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Fuel Type</label>
                                            <p class="form-control-plaintext">{{ ucfirst($receipt->fuel_type) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Uploaded Date</label>
                                            <p class="form-control-plaintext">
                                                {{ $receipt->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($receipt->notes)
                                    <div class="form-group">
                                        <label class="form-label">Notes</label>
                                        <p class="form-control-plaintext">{{ $receipt->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Receipt Photo -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Receipt Photo</h6>
                            </div>
                            <div class="card-body text-center">
                                @if($receipt->receipt_photo)
                                    <img src="{{ asset('storage/' . $receipt->receipt_photo) }}" alt="Receipt Photo"
                                        class="img-fluid rounded" style="max-height: 500px;">
                                @else
                                    <p class="text-muted">No receipt photo available</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Fuel Request Details -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Fuel Request Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Request ID</label>
                                    <p class="form-control-plaintext">{{ $receipt->fuelRequest->id }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Vehicle</label>
                                    <p class="form-control-plaintext">
                                        {{ $receipt->fuelRequest->vehicle->make }}
                                        {{ $receipt->fuelRequest->vehicle->model }}
                                        ({{ $receipt->fuelRequest->vehicle->license_plate }})
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Station</label>
                                    <p class="form-control-plaintext">{{ $receipt->station->name }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Client</label>
                                    <p class="form-control-plaintext">{{ $receipt->client->company_name }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Request Date</label>
                                    <p class="form-control-plaintext">
                                        {{ $receipt->fuelRequest->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Information -->
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6>Upload Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Uploaded By</label>
                                    <p class="form-control-plaintext">{{ $receipt->uploadedBy->name }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Role</label>
                                    <p class="form-control-plaintext">
                                        @if($receipt->uploadedBy->isStationManager())
                                            Station Manager
                                        @elseif($receipt->uploadedBy->isFuelPumper())
                                            Fuel Pumper
                                        @else
                                            {{ ucfirst($receipt->uploadedBy->role) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Verification Actions (Treasury Only) -->
                        @if(auth()->user()->isTreasury() && $receipt->status === 'pending')
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Verification Actions</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('receipts.verify', $receipt) }}" class="mb-3">
                                        @csrf
                                        <input type="hidden" name="action" value="verify">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Verification Notes</label>
                                            <textarea class="form-control" name="notes" rows="3"
                                                placeholder="Add verification notes..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="material-symbols-rounded me-1">check_circle</i>
                                            Verify Receipt
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('receipts.reject', $receipt) }}">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="form-label">Rejection Notes</label>
                                            <textarea class="form-control" name="notes" rows="3"
                                                placeholder="Add rejection reason..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="material-symbols-rounded me-1">cancel</i>
                                            Reject Receipt
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Verification Details (if verified/rejected) -->
                        @if($receipt->status !== 'pending' && $receipt->verifiedBy)
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Verification Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Verified By</label>
                                        <p class="form-control-plaintext">{{ $receipt->verifiedBy->name }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Verification Date</label>
                                        <p class="form-control-plaintext">{{ $receipt->verified_at->format('M d, Y H:i') }}</p>
                                    </div>

                                    @if($receipt->verification_notes)
                                        <div class="form-group">
                                            <label class="form-label">Verification Notes</label>
                                            <p class="form-control-plaintext">{{ $receipt->verification_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection