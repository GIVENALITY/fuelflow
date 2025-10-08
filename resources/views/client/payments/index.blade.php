@extends('layouts.app')

@section('title', 'My Payments')
@section('breadcrumb', 'Payments')
@section('page-title', 'Payment History')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Payments Table -->
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>My Payment History</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                                    <span class="font-weight-bold ms-1">{{ $payments->total() ?? 0 }}</span> total payments
                                </p>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <a href="{{ route('client-portal.payments.create') }}" class="btn btn-success btn-sm">
                                    <i class="material-symbols-rounded">add</i> New Payment
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        @if($payments->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bank</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">#{{ $payment->id }}</h6>
                                                            @if($payment->reference_number)
                                                            <p class="text-xs text-secondary mb-0">Ref: {{ $payment->reference_number }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-bold">TZS {{ number_format($payment->amount, 0) }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ $payment->bank_name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($payment->status === 'pending')
                                                        <span class="badge badge-sm bg-gradient-warning">Pending Verification</span>
                                                    @elseif($payment->status === 'verified')
                                                        <span class="badge badge-sm bg-gradient-success">Verified</span>
                                                    @elseif($payment->status === 'approved')
                                                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                                                    @elseif($payment->status === 'rejected')
                                                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($payment->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if($payment->proof_of_payment_path)
                                                    <a href="{{ asset('storage/' . $payment->proof_of_payment_path) }}" 
                                                       target="_blank"
                                                       class="btn btn-link text-secondary mb-0"
                                                       title="View Proof of Payment">
                                                        <i class="fa fa-file text-xs me-2"></i>View POP
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($payments->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $payments->links() }}
                            </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-credit-card text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No payments found</h5>
                                <p class="text-muted">Your payment history will appear here once you submit payments.</p>
                                <a href="{{ route('client-portal.payments.create') }}" class="btn btn-success mt-3">
                                    <i class="fas fa-plus me-2"></i>Submit Your First Payment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

