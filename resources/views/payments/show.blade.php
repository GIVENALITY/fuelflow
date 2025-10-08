@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-gradient-info p-3">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-white mb-0">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Payment Details
                            </h6>
                        </div>
                        <div class="col-6 text-end">
                            @if(auth()->user()->isTreasury() || auth()->user()->isAdmin())
                                <a href="{{ route('payments.pending') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Pending
                                </a>
                            @else
                                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-left me-1"></i>Back to Payments
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Payment Status Banner -->
                    @if($payment->status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-hourglass-half me-2"></i>
                            <strong>Status:</strong> Pending Verification
                        </div>
                    @elseif($payment->status === 'verified' || $payment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Status:</strong> Verified and Applied
                        </div>
                    @elseif($payment->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Status:</strong> Rejected
                        </div>
                    @endif

                    <!-- Payment Details -->
                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Payment Information</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Payment ID:</strong></p>
                            <p>#{{ $payment->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Client:</strong></p>
                            <p>{{ $payment->client->company_name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Payment Amount:</strong></p>
                            <h4 class="text-success">TZS {{ number_format($payment->amount, 0) }}</h4>
                        </div>
                        @if(auth()->user()->isTreasury() || auth()->user()->isAdmin())
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Client Balance:</strong></p>
                            <h5 class="text-danger">TZS {{ number_format($payment->client->current_balance ?? 0, 0) }}</h5>
                        </div>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Bank:</strong></p>
                            <p>{{ $payment->bank_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Reference Number:</strong></p>
                            <p>{{ $payment->reference_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Payment Date:</strong></p>
                            <p>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Submitted On:</strong></p>
                            <p>{{ $payment->created_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>

                    @if($payment->receipt)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-sm mb-2"><strong>Linked Receipt:</strong></p>
                            <p>Receipt #{{ $payment->receipt->id }}</p>
                        </div>
                    </div>
                    @endif

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
                                    <div class="mb-3">
                                        <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-eye me-2"></i>View Proof of Payment
                                        </a>
                                        <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" download class="btn btn-outline-primary ms-2">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                    </div>
                                    
                                    @php
                                        $fileExtension = pathinfo($payment->proof_of_payment, PATHINFO_EXTENSION);
                                    @endphp
                                    
                                    @if(strtolower($fileExtension) === 'pdf')
                                        <div class="alert alert-info">
                                            <i class="fas fa-file-pdf me-2"></i>
                                            <strong>PDF Document</strong> - Click "View" to open in a new tab
                                        </div>
                                        <!-- Embedded PDF Preview (optional) -->
                                        <div class="mt-3" style="height: 600px;">
                                            <iframe src="{{ asset('storage/' . $payment->proof_of_payment) }}" 
                                                    width="100%" 
                                                    height="100%" 
                                                    style="border: 1px solid #ddd; border-radius: 8px;">
                                            </iframe>
                                        </div>
                                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $payment->proof_of_payment) }}" 
                                                 alt="Proof of Payment" 
                                                 class="img-fluid border-radius-md" 
                                                 style="max-height: 500px; border: 1px solid #ddd;">
                                        </div>
                                    @else
                                        <div class="alert alert-secondary">
                                            <i class="fas fa-file me-2"></i>
                                            <strong>{{ strtoupper($fileExtension) }} File</strong> - Click "Download" to view
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-muted">No proof of payment attached.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Submitted By (Treasury View) -->
                    @if(auth()->user()->isTreasury() || auth()->user()->isAdmin())
                        @if($payment->submittedBy)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-sm mb-2"><strong>Submitted By:</strong></p>
                                <p>{{ $payment->submittedBy->name }}</p>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Actions (Treasury Only) -->
                    @if((auth()->user()->isTreasury() || auth()->user()->isAdmin()) && $payment->status === 'pending')
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3"><i class="fas fa-tasks me-2"></i>Actions</h6>
                                
                                <!-- Verify/Approve Button -->
                                <form action="{{ route('payments.verify', $payment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to verify and approve this payment?');">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg me-2">
                                        <i class="fas fa-check me-2"></i>Verify & Approve Payment
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button type="button" class="btn btn-danger btn-lg" onclick="alert('Rejection feature coming soon. Please contact development team.');">
                                    <i class="fas fa-times me-2"></i>Reject Payment
                                </button>
                            </div>
                        </div>
                    @elseif($payment->status === 'verified' || $payment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            This payment was verified and applied successfully.
                        </div>
                    @elseif($payment->status === 'rejected')
                        @if($payment->rejection_reason)
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Rejection Reason:</strong> {{ $payment->rejection_reason }}
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

