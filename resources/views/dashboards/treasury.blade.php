@extends('layouts.app')

@section('title', 'Treasury Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Treasury Dashboard</h4>
                        <p class="text-sm text-secondary mb-0">Financial overview and payment processing</p>
                    </div>
                    <div>
                        <a href="{{ route('receipts.pending') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded me-2">pending_actions</i>Review Receipts
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Outstanding Balances</p>
                                    <h5 class="font-weight-bolder mb-0">TZS {{ number_format($outstandingBalances ?? 0) }}
                                    </h5>
                                    <p class="mb-0 text-warning text-sm">
                                        <span class="text-warning font-weight-bolder">Total</span> owed
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">account_balance_wallet</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Receipts</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingReceipts ?? 0 }}</h5>
                                    <p class="mb-0 text-danger text-sm">
                                        <span class="text-danger font-weight-bolder">Awaiting</span> verification
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">receipt_long</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Overdue Accounts</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $overdueAccounts ?? 0 }}</h5>
                                    <p class="mb-0 text-danger text-sm">
                                        <span class="text-danger font-weight-bolder">Requires</span> attention
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">warning</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Monthly Revenue</p>
                                    <h5 class="font-weight-bolder mb-0">TZS {{ number_format($monthlyRevenue ?? 0) }}</h5>
                                    <p class="mb-0 text-success text-sm">
                                        <span class="text-success font-weight-bolder">This month</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">attach_money</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="row">
            <!-- Pending Receipts -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">receipt_long</i>
                            <h6 class="mb-0">Pending Receipt Verification</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Receipts awaiting your verification</p>
                    </div>
                    <div class="card-body p-3">
                        @if($pendingReceiptsList && $pendingReceiptsList->count() > 0)
                            @foreach($pendingReceiptsList as $receipt)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-sm bg-gradient-warning rounded-circle">
                                            <span class="text-white text-xs font-weight-bold">#{{ $receipt->id }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-sm font-weight-bold">
                                                    {{ $receipt->client->company_name ?? 'N/A' }}</h6>
                                                <p class="text-xs text-secondary mb-0">Request
                                                    #{{ $receipt->fuelRequest->id ?? 'N/A' }} • TZS
                                                    {{ number_format($receipt->amount ?? 0) }}</p>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i
                                                        class="material-symbols-rounded text-xs me-1">schedule</i>{{ $receipt->created_at ? $receipt->created_at->format('M d, Y H:i') : 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="material-symbols-rounded text-muted" style="font-size: 48px;">receipt_long</i>
                                <h6 class="text-muted mt-2">No pending receipts</h6>
                                <p class="text-sm text-muted">All receipts have been verified.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Treasury Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">account_balance</i>
                            <h6 class="mb-0">Treasury Actions</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Financial management tools</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('receipts.pending') }}" class="btn btn-outline-warning btn-sm">
                                <i class="material-symbols-rounded me-2">pending_actions</i>Review Pending Receipts
                            </a>
                            <a href="{{ route('clients.overdue') }}" class="btn btn-outline-danger btn-sm">
                                <i class="material-symbols-rounded me-2">warning</i>View Overdue Accounts
                            </a>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="material-symbols-rounded me-2">payments</i>Process Payments
                            </a>
                            <a href="{{ route('reports.financial') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">analytics</i>Financial Reports
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-2">assessment</i>All Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection