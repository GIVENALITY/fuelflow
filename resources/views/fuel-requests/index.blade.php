@extends('layouts.app')

@section('title', 'Fuel Requests - FuelFlow')
@section('breadcrumb', 'Fuel Requests')
@section('page-title', 'Fuel Request Management')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Fuel Requests</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">{{ $totalRequests ?? 0 }}</span> total requests
                        </p>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        @if(auth()->user()->isClient())
                        <a href="{{ route('fuel-requests.create') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">add</i> New Request
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vehicle</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel Type</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests ?? [] as $request)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $request->client->company_name ?? 'N/A' }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $request->client->contact_person ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $request->vehicle->plate_number ?? 'N/A' }}</p>
                                    <p class="text-xs text-secondary mb-0">{{ $request->vehicle->make ?? 'N/A' }} {{ $request->vehicle->model ?? '' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    <span class="text-xs font-weight-bold">{{ ucfirst($request->fuel_type) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $request->quantity_requested }} L</span>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">${{ number_format($request->total_amount, 2) }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    @if($request->status === 'pending')
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    @elseif($request->status === 'approved')
                                        <span class="badge badge-sm bg-gradient-info">Approved</span>
                                    @elseif($request->status === 'in_progress')
                                        <span class="badge badge-sm bg-gradient-primary">In Progress</span>
                                    @elseif($request->status === 'dispensed')
                                        <span class="badge badge-sm bg-gradient-success">Dispensed</span>
                                    @elseif($request->status === 'completed')
                                        <span class="badge badge-sm bg-gradient-success">Completed</span>
                                    @elseif($request->status === 'rejected')
                                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($request->status) }}</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <a href="{{ route('fuel-requests.show', $request->id) }}" class="btn btn-link text-secondary mb-0">
                                        <i class="fa fa-eye text-xs me-2"></i>View
                                    </a>
                                    @if(auth()->user()->canApproveRequests() && $request->status === 'pending')
                                    <form action="{{ route('fuel-requests.approve', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-success mb-0">
                                            <i class="fa fa-check text-xs me-2"></i>Approve
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No fuel requests found</td>
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
                <h6>Request Summary</h6>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="material-icons text-warning text-gradient">schedule</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Pending Requests</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $pendingRequests ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="material-icons text-info text-gradient">check_circle</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Approved Requests</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $approvedRequests ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="material-icons text-success text-gradient">local_gas_station</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Completed Requests</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $completedRequests ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="timeline-block">
                        <span class="timeline-step">
                            <i class="material-icons text-primary text-gradient">analytics</i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">Total Requests</h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $totalRequests ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
