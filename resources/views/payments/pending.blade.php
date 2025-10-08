@extends('layouts.app')

@section('title', 'Pending Payments')
@section('breadcrumb', 'Payments')
@section('page-title', 'Pending Payment Verification')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Pending Payments Table -->
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Pending Payment Verification</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-clock text-warning" aria-hidden="true"></i>
                                    <span class="font-weight-bold ms-1">{{ $payments->count() }}</span> pending payments
                                </p>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="material-symbols-rounded">list</i> All Payments
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
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bank</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Receipt</th>
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
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-0 text-sm">{{ $payment->client->company_name ?? 'N/A' }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $payment->client->contact_person ?? '' }}</p>
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
                                                    <p class="text-xs text-secondary mb-0">Submitted: {{ $payment->created_at->format('M d, Y') }}</p>
                                                </td>
                                                <td>
                                                    @if($payment->receipt)
                                                        <span class="text-xs">Receipt #{{ $payment->receipt->id }}</span>
                                                    @else
                                                        <span class="text-xs text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <div class="btn-group" role="group">
                                                        @if($payment->proof_of_payment)
                                                        <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" 
                                                           target="_blank"
                                                           class="btn btn-link text-info mb-0 px-2"
                                                           title="View Proof of Payment">
                                                            <i class="fa fa-file text-xs"></i>
                                                        </a>
                                                        @endif
                                                        
                                                        <a href="{{ route('payments.show', $payment->id) }}" 
                                                           class="btn btn-link text-secondary mb-0 px-2"
                                                           title="View Details">
                                                            <i class="fa fa-eye text-xs"></i>
                                                        </a>
                                                        
                                                        @if(auth()->user()->isTreasury() || auth()->user()->isAdmin())
                                                        <form action="{{ route('payments.verify', $payment->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to verify this payment?');">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-link text-success mb-0 px-2"
                                                                    title="Verify Payment">
                                                                <i class="fa fa-check text-xs"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No Pending Payments</h5>
                                <p class="text-muted">All payments have been verified!</p>
                                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary mt-3">
                                    <i class="fas fa-list me-2"></i>View All Payments
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        @if($payments->count() > 0)
        <div class="row mt-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">pending</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Pending Count</p>
                            <h4 class="mb-0">{{ $payments->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">payments</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Amount</p>
                            <h4 class="mb-0">TZS {{ number_format($payments->sum('amount'), 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

