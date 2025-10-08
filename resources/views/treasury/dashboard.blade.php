@extends('layouts.app')

@section('title', 'Treasury Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1">
                <i class="fas fa-university me-2"></i>Treasury Dashboard
            </h4>
            <p class="text-sm text-secondary mb-0">Payment verification and financial management</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-hourglass-half opacity-10"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Pending Payments</p>
                        <h4 class="mb-0">{{ $pendingCount }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-warning text-sm font-weight-bolder">{{ $pendingCount }}</span> awaiting verification</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-money-bill-wave opacity-10"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Pending Amount</p>
                        <h4 class="mb-0">TZS {{ number_format($totalPendingAmount, 0) }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Total</span> pending verification</p>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                        <i class="fas fa-check-circle opacity-10"></i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Verified Today</p>
                        <h4 class="mb-0">{{ $verifiedToday }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Payments</span> processed today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('treasury.payments.pending') }}" class="btn btn-warning w-100">
                                <i class="fas fa-hourglass-half me-2"></i>Pending Payments
                                @if($pendingCount > 0)
                                    <span class="badge bg-white text-warning ms-2">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('treasury.payments.all') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-list me-2"></i>All Payments
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-users me-2"></i>Client Accounts
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.financial') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-chart-line me-2"></i>Financial Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Pending Payments -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h6><i class="fas fa-clock me-2"></i>Recent Pending Payments</h6>
                        <a href="{{ route('treasury.payments.pending') }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    @if($pendingPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bank</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Submitted</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingPayments as $payment)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $payment->client->company_name ?? 'N/A' }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $payment->client->contact_person ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $payment->bank_name }}</p>
                                            @if($payment->reference_number)
                                                <p class="text-xs text-secondary mb-0">Ref: {{ $payment->reference_number }}</p>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-sm font-weight-bold">TZS {{ number_format($payment->amount, 0) }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs">{{ $payment->created_at->diffForHumans() }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('treasury.payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye me-1"></i>Review
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-secondary mt-3">No pending payments at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

