@extends('layouts.app')

@section('title', 'Fuel Pumper Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Fuel Pumper Dashboard</h4>
                        <p class="text-sm text-secondary mb-0">Welcome back, {{ Auth::user()->name }}. Here are your
                            assigned tasks.</p>
                    </div>
                    <div>
                        <a href="{{ route('fuel-requests.my-assignments') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded me-2">assignment_ind</i>My Assignments
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Assigned Tasks</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $assignedRequests ?? 0 }}</h5>
                                    <p class="mb-0 text-info text-sm">
                                        <span class="text-info font-weight-bolder">Ready</span> for processing
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">assignment</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Completed Today</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $completedToday ?? 0 }}</h5>
                                    <p class="mb-0 text-success text-sm">
                                        <span class="text-success font-weight-bolder">Tasks</span> finished
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">check_circle</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Fuel Dispensed</p>
                                    <h5 class="font-weight-bolder mb-0">{{ number_format($fuelDispensed ?? 0, 2) }}L</h5>
                                    <p class="mb-0 text-warning text-sm">
                                        <span class="text-warning font-weight-bolder">Today's</span> total
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">local_gas_station</i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Pending Tasks</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $pendingTasks ?? 0 }}</h5>
                                    <p class="mb-0 text-danger text-sm">
                                        <span class="text-danger font-weight-bolder">Awaiting</span> completion
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="material-symbols-rounded text-lg opacity-10">pending_actions</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="row">
            <!-- My Assignments -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">assignment_ind</i>
                            <h6 class="mb-0">My Current Assignments</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Tasks assigned to you for completion</p>
                    </div>
                    <div class="card-body p-3">
                        @if($assignedRequestsList && $assignedRequestsList->count() > 0)
                            @foreach($assignedRequestsList as $request)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="avatar avatar-sm bg-gradient-{{ $request->status === 'approved' ? 'success' : 'warning' }} rounded-circle">
                                            <span class="text-white text-xs font-weight-bold">#{{ $request->id }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-sm font-weight-bold">
                                                    {{ $request->client->company_name ?? 'N/A' }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $request->vehicle->plate_number ?? 'N/A' }} •
                                                    {{ number_format($request->quantity_requested, 2) }}L</p>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i
                                                        class="material-symbols-rounded text-xs me-1">local_gas_station</i>{{ $request->station->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $request->status === 'approved' ? 'success' : 'warning' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="material-symbols-rounded text-muted" style="font-size: 48px;">assignment</i>
                                <h6 class="text-muted mt-2">No current assignments</h6>
                                <p class="text-sm text-muted">You don't have any fuel requests assigned to you at the moment.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <i class="material-symbols-rounded text-primary me-2">build</i>
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <p class="text-sm text-secondary mb-0">Common tasks and tools</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('fuel-requests.my-assignments') }}" class="btn btn-outline-primary btn-sm">
                                <i class="material-symbols-rounded me-2">assignment_ind</i>View My Assignments
                            </a>
                            <a href="{{ route('fuel-requests.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="material-symbols-rounded me-2">assignment</i>View All Requests
                            </a>
                            <a href="{{ route('receipts.create') }}" class="btn btn-outline-success btn-sm">
                                <i class="material-symbols-rounded me-2">receipt_long</i>Upload Receipt
                            </a>
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="material-symbols-rounded me-2">person</i>Update Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection