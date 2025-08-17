@extends('layouts.app')

@section('title', 'Billing - FuelFlow')

@section('breadcrumb', 'Billing')
@section('page-title', 'Billing Management')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Billing Information</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">30 done</span> this month
                        </p>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                            <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fuel Type</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills ?? [] as $bill)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $bill->customer_name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $bill->customer_email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $bill->fuel_type }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">{{ $bill->quantity }} L</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">${{ number_format($bill->amount, 2) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    @if($bill->status === 'paid')
                                        <span class="badge badge-sm bg-gradient-success">Paid</span>
                                    @elseif($bill->status === 'pending')
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-danger">Overdue</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('billing.show', $bill->id) }}" class="btn btn-link text-secondary mb-0">
                                        <i class="fa fa-eye text-xs me-2"></i>View
                                    </a>
                                    <a href="{{ route('billing.edit', $bill->id) }}" class="btn btn-link text-secondary mb-0">
                                        <i class="fa fa-edit text-xs me-2"></i>Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No billing records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Billing Summary</h6>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="material-icons text-success text-gradient">receipt_long</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Total Bills</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $totalBills ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="material-icons text-info text-gradient">payments</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Paid Amount</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">${{ number_format($paidAmount ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="material-icons text-warning text-gradient">schedule</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Pending Amount</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">${{ number_format($pendingAmount ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <div class="timeline-block">
                        <span class="timeline-step">
                            <i class="material-icons text-danger text-gradient">warning</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Overdue Amount</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">${{ number_format($overdueAmount ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header pb-0">
                <h6>Quick Actions</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('billing.create') }}" class="btn btn-primary btn-sm">
                        <i class="material-symbols-rounded">add</i> Create New Bill
                    </a>
                    <a href="{{ route('billing.export') }}" class="btn btn-info btn-sm">
                        <i class="material-symbols-rounded">download</i> Export Bills
                    </a>
                    <a href="{{ route('billing.reports') }}" class="btn btn-success btn-sm">
                        <i class="material-symbols-rounded">analytics</i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
