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
                        <a href="{{ route('payments.pending.list') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded me-2">pending_actions</i>Review Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">account_balance</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Outstanding Balances</p>
                            <h4 class="mb-0">TSh {{ number_format($outstandingBalances ?? 0) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5%</span> from last month</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-warning shadow-warning text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">pending_actions</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Pending Receipts</p>
                            <h4 class="mb-0">{{ $pendingReceipts ?? 0 }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-warning text-sm font-weight-bolder">Awaiting</span> verification</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-danger shadow-danger text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">warning</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Overdue Accounts</p>
                            <h4 class="mb-0">{{ $overdueAccounts ?? 0 }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Requires</span> attention</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-symbols-rounded opacity-10">trending_up</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Monthly Revenue</p>
                            <h4 class="mb-0">TSh {{ number_format($monthlyRevenue ?? 0) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+12%</span> from last month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Quick Actions -->
        <div class="row">
            <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Pending Receipts</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                                    <span class="font-weight-bold ms-1">Awaiting verification</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingReceiptsList ?? [] as $receipt)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $receipt->client->company_name ?? 'N/A' }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $receipt->client->contact_person ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">TSh {{ number_format($receipt->total_amount ?? 0) }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $receipt->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm bg-gradient-warning">{{ $receipt->status ?? 'Pending' }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('receipts.show', $receipt->id) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View details">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-sm text-secondary mb-0">No pending receipts found</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                <h6 class="mb-0">Quick Actions</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('payments.pending.list') }}" class="btn btn-outline-warning btn-sm">
                                <i class="material-symbols-rounded me-2">pending_actions</i>Review Pending Payments
                            </a>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-danger btn-sm">
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
