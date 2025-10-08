@extends('layouts.app')

@section('title', 'Submit Payment')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-gradient-success p-3">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="text-white mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>Submit Payment
                            </h6>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="text-white"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <!-- Display Error Message -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <!-- Current Balance Info -->
                    <div class="alert alert-warning mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-circle me-2"></i>Current Outstanding Balance
                                </h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4 class="mb-0 text-danger">TZS {{ number_format($currentBalance, 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('client-portal.payments.store') }}" method="POST" enctype="multipart/form-data" onsubmit="console.log('Payment form submitting...'); return true;">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Bank Name *</label>
                                    <select class="form-control @error('bank_name') is-invalid @enderror" 
                                            name="bank_name" 
                                            required>
                                        <option value="">-- Select Bank --</option>
                                        <option value="CRDB Bank" {{ old('bank_name') == 'CRDB Bank' ? 'selected' : '' }}>CRDB Bank</option>
                                        <option value="NMB Bank" {{ old('bank_name') == 'NMB Bank' ? 'selected' : '' }}>NMB Bank</option>
                                        <option value="NBC Bank" {{ old('bank_name') == 'NBC Bank' ? 'selected' : '' }}>NBC Bank</option>
                                        <option value="Exim Bank" {{ old('bank_name') == 'Exim Bank' ? 'selected' : '' }}>Exim Bank</option>
                                        <option value="Standard Chartered" {{ old('bank_name') == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                        <option value="Stanbic Bank" {{ old('bank_name') == 'Stanbic Bank' ? 'selected' : '' }}>Stanbic Bank</option>
                                        <option value="DTB Bank" {{ old('bank_name') == 'DTB Bank' ? 'selected' : '' }}>DTB Bank</option>
                                        <option value="Azania Bank" {{ old('bank_name') == 'Azania Bank' ? 'selected' : '' }}>Azania Bank</option>
                                        <option value="Other" {{ old('bank_name') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Amount (TZS) *</label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           name="amount" 
                                           value="{{ old('amount') }}" 
                                           min="1"
                                           step="0.01"
                                           placeholder="Enter amount"
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Payment Date *</label>
                                    <input type="date" 
                                           class="form-control @error('payment_date') is-invalid @enderror" 
                                           name="payment_date" 
                                           value="{{ old('payment_date', date('Y-m-d')) }}" 
                                           max="{{ date('Y-m-d') }}"
                                           required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Reference Number (Optional)</label>
                                    <input type="text" 
                                           class="form-control @error('reference_number') is-invalid @enderror" 
                                           name="reference_number" 
                                           value="{{ old('reference_number') }}" 
                                           placeholder="Bank reference or transaction number">
                                    @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-static mb-4">
                                    <label>Attach Proof of Payment (POP) *</label>
                                    <input type="file" 
                                           class="form-control @error('proof_of_payment') is-invalid @enderror" 
                                           name="proof_of_payment" 
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           required>
                                    @error('proof_of_payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Accepted formats: PDF, JPG, PNG (Max: 5MB)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-static mb-4">
                                    <label>Additional Notes (Optional)</label>
                                    <textarea class="form-control" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Any additional information about this payment">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> After submitting, your payment will be reviewed by the treasury department. 
                                    Once verified, it will be deducted from your outstanding balance.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Payment
                                </button>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

