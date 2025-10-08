@extends('layouts.app')

@section('title', 'Payments')
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
                                <h6>Payment History</h6>
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                @if(auth()->user()->isClient())
                                <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                                    <i class="material-symbols-rounded">add</i> New Payment
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Payment ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Method</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">#{{ $payment->id }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm font-weight-bold">${{ number_format($payment->amount, 2) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-sm">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-sm">{{ $payment->payment_date->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm {{ $payment->status_badge }}">
                                                        {{ $payment->status_display }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('payments.show', $payment) }}"
                                                        class="btn btn-outline-primary btn-sm"
                                                        title="View Payment Details">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="d-none d-md-inline ms-1">View</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="card-footer">
                                {{ $payments->links() }}
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-credit-card text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No payments found</h5>
                                <p class="text-muted">Your payment history will appear here.</p>
                                @if(auth()->user()->isClient())
                                <a href="{{ route('payments.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-2"></i>Submit Your First Payment
                                </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection