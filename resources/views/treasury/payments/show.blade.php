@extends('layouts.app')

@section('title', 'Review Payment')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-gradient-info p-3">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-white mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Payment Review
                            </h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('treasury.payments.pending') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left me-1"></i>Back to Pending
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Payment Status Banner -->
                    @if($payment->status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-hourglass-half me-2"></i>
                            <strong>Status:</strong> Awaiting Verification
                        </div>
                    @elseif($payment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Status:</strong> Approved and Applied
                        </div>
                    @elseif($payment->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Status:</strong> Rejected
                        </div>
                    @endif

                    <!-- Payment Details -->
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Payment Details</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Client:</strong></p>
                            <p>{{ $payment->client->company_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Contact Person:</strong></p>
                            <p>{{ $payment->client->contact_person }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Payment Amount:</strong></p>
                            <h4 class="text-success">TZS {{ number_format($payment->amount, 0) }}</h4>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Current Balance:</strong></p>
                            <h5 class="text-danger">TZS {{ number_format($payment->client->current_balance ?? 0, 0) }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Bank:</strong></p>
                            <p>{{ $payment->bank_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Reference Number:</strong></p>
                            <p>{{ $payment->reference_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Payment Date:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($payment->payment_date)->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Submitted:</strong></p>
                            <p>{{ $payment->created_at->format('F d, Y - h:i A') }}</p>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="text-sm mb-2"><strong>Notes:</strong></p>
                                <p class="bg-light p-3 border-radius-md">{{ $payment->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Proof of Payment -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3"><i class="fas fa-paperclip me-2"></i>Proof of Payment</h6>
                            @if($payment->proof_of_payment)
                                <div class="border p-3 border-radius-md">
                                    <a href="{{ Storage::url($payment->proof_of_payment) }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Download Proof of Payment
                                    </a>
                                    @if(str_ends_with($payment->proof_of_payment, '.pdf'))
                                        <p class="text-sm text-muted mt-2"><i class="fas fa-file-pdf me-1"></i>PDF Document</p>
                                    @else
                                        <div class="mt-3">
                                            <img src="{{ Storage::url($payment->proof_of_payment) }}" alt="Proof of Payment" class="img-fluid border-radius-md" style="max-height: 400px;">
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-muted">No proof of payment attached.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($payment->status === 'pending')
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3"><i class="fas fa-tasks me-2"></i>Actions</h6>
                                
                                <!-- Approve Button -->
                                <form action="{{ route('treasury.payments.approve', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to approve this payment? The client balance will be updated.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-lg me-2">
                                        <i class="fas fa-check me-2"></i>Approve Payment
                                    </button>
                                </form>

                                <!-- Reject Button (triggers modal) -->
                                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>Reject Payment
                                </button>
                            </div>
                        </div>
                    @elseif($payment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            This payment was approved on {{ $payment->verified_at->format('F d, Y - h:i A') }}
                        </div>
                    @elseif($payment->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Rejection Reason:</strong> {{ $payment->rejection_reason }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('treasury.payments.reject', $payment->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Reject Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this payment:</p>
                    <div class="input-group input-group-static mb-3">
                        <label>Rejection Reason *</label>
                        <textarea class="form-control" name="rejection_reason" rows="4" required placeholder="Enter detailed reason for rejection"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Reject Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

