@extends('layouts.app')

@section('title', 'Dashboard - FuelFlow')

@section('breadcrumb', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    @if(auth()->user()->isAdmin())
        <!-- Admin Dashboard Stats -->
        <x-stat-card 
            title="Total Revenue" 
            value="${{ number_format($totalRevenue ?? 0, 2) }}"
            icon="payments"
            color="success"
            trend="12"
        />
        
        <x-stat-card 
            title="Active Clients" 
            value="{{ $activeClients ?? 0 }}"
            icon="people"
            color="primary"
            trend="5"
        />
        
        <x-stat-card 
            title="Total Stations" 
            value="{{ $totalStations ?? 0 }}"
            icon="local_gas_station"
            color="info"
            trend="2"
        />
        
        <x-stat-card 
            title="Pending Approvals" 
            value="{{ $pendingApprovals ?? 0 }}"
            icon="pending_actions"
            color="warning"
            trend="-3"
            trendDirection="down"
        />
    @elseif(auth()->user()->isStationManager())
        <!-- Station Manager Dashboard Stats -->
        <x-stat-card 
            title="Today's Requests" 
            value="{{ $todayRequests ?? 0 }}"
            icon="assignment"
            color="primary"
        />
        
        <x-stat-card 
            title="Pending Approvals" 
            value="{{ $pendingApprovals ?? 0 }}"
            icon="pending_actions"
            color="warning"
        />
        
        <x-stat-card 
            title="Fuel Dispensed Today" 
            value="{{ number_format($fuelDispensedToday ?? 0, 2) }} L"
            icon="local_gas_station"
            color="success"
        />
        
        <x-stat-card 
            title="Available Staff" 
            value="{{ $availableStaff ?? 0 }}"
            icon="people"
            color="info"
        />
    @elseif(auth()->user()->isFuelPumper())
        <!-- Fuel Pumper Dashboard Stats -->
        <x-stat-card 
            title="Assigned Requests" 
            value="{{ $assignedRequests ?? 0 }}"
            icon="assignment"
            color="primary"
        />
        
        <x-stat-card 
            title="Completed Today" 
            value="{{ $completedToday ?? 0 }}"
            icon="task_alt"
            color="success"
        />
        
        <x-stat-card 
            title="Fuel Dispensed" 
            value="{{ number_format($fuelDispensed ?? 0, 2) }} L"
            icon="local_gas_station"
            color="info"
        />
        
        <x-stat-card 
            title="Pending Tasks" 
            value="{{ $pendingTasks ?? 0 }}"
            icon="pending"
            color="warning"
        />
    @elseif(auth()->user()->isTreasury())
        <!-- Treasury Dashboard Stats -->
        <x-stat-card 
            title="Outstanding Balances" 
            value="${{ number_format($outstandingBalances ?? 0, 2) }}"
            icon="account_balance_wallet"
            color="warning"
        />
        
        <x-stat-card 
            title="Pending Receipts" 
            value="{{ $pendingReceipts ?? 0 }}"
            icon="receipt_long"
            color="info"
        />
        
        <x-stat-card 
            title="Overdue Accounts" 
            value="{{ $overdueAccounts ?? 0 }}"
            icon="warning"
            color="danger"
        />
        
        <x-stat-card 
            title="Monthly Revenue" 
            value="${{ number_format($monthlyRevenue ?? 0, 2) }}"
            icon="trending_up"
            color="success"
            trend="8"
        />
    @else
        <!-- Default Dashboard Stats -->
        <x-stat-card 
            title="Total Fuel Sales" 
            value="${{ number_format($totalSales ?? 0, 2) }}"
            icon="local_gas_station"
            color="dark"
            trend="3"
        />
        
        <x-stat-card 
            title="Active Clients" 
            value="{{ $activeClients ?? 0 }}"
            icon="people"
            color="primary"
            trend="5"
        />
        
        <x-stat-card 
            title="Today's Deliveries" 
            value="{{ $todayDeliveries ?? 0 }}"
            icon="local_shipping"
            color="success"
            trend="-2"
            trendDirection="down"
        />
        
        <x-stat-card 
            title="Pending Invoices" 
            value="{{ $pendingInvoices ?? 0 }}"
            icon="receipt_long"
            color="info"
            trend="1"
        />
    @endif
</div>

<div class="row mt-4">
    @if(auth()->user()->isAdmin())
        <!-- Admin Dashboard Content -->
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>System Overview</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">All systems operational</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requests</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stations ?? [] as $station)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $station->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $station->full_address }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $station->status_badge }}">{{ $station->status_display_name }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $station->getTodayRequests()->count() }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">{{ number_format($station->diesel_utilization, 1) }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info w-{{ $station->diesel_utilization }}" role="progressbar" aria-valuenow="{{ $station->diesel_utilization }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No stations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->isStationManager())
        <!-- Station Manager Dashboard Content -->
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>Pending Fuel Requests</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-clock text-warning" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">{{ $pendingApprovals ?? 0 }} pending</span> approval
                            </p>
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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Urgency</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingRequests ?? [] as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $request->client->company_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $request->client->contact_person }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->vehicle->plate_number }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $request->vehicle->full_description }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $request->formatted_quantity_requested }}</span>
                                        <br>
                                        <span class="text-xs text-secondary">{{ $request->fuel_type }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $request->urgency_badge }}">{{ ucfirst($request->urgency_level) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('fuel-requests.show', $request->id) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="fa fa-eye text-xs me-2"></i>Review
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No pending requests</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->isFuelPumper())
        <!-- Fuel Pumper Dashboard Content -->
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>My Assigned Requests</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-tasks text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">{{ $assignedRequests ?? 0 }} assigned</span> to you
                            </p>
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
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myAssignedRequests ?? [] as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $request->client->company_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $request->vehicle->plate_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->vehicle->full_description }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $request->formatted_quantity_requested }}</span>
                                        <br>
                                        <span class="text-xs text-secondary">{{ $request->fuel_type }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm {{ $request->status_badge }}">{{ $request->status_display_name }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('fuel-requests.dispense', $request->id) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="fa fa-gas-pump text-xs me-2"></i>Dispense
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No assigned requests</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->isTreasury())
        <!-- Treasury Dashboard Content -->
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>Pending Receipt Verification</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-clock text-warning" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">{{ $pendingReceipts ?? 0 }} receipts</span> awaiting verification
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingReceiptsList ?? [] as $receipt)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $receipt->client->company_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $receipt->fuelRequest->vehicle->plate_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $receipt->formatted_amount }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $receipt->station->name }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $receipt->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('receipts.verify', $receipt->id) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="fa fa-check text-xs me-2"></i>Verify
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No pending receipts</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Default Dashboard Content -->
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>Recent Fuel Requests</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">30 done</span> this month
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fuel Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Sample Client</h6>
                                                <p class="text-xs text-secondary mb-0">123 Main St</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold">Diesel</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">500 L</span>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-sm bg-gradient-success">Completed</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Quick Actions</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-grid gap-2">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">person_add</i> Add Client
                        </a>
                        <a href="{{ route('stations.create') }}" class="btn btn-info btn-sm">
                            <i class="material-symbols-rounded">add_location</i> Add Station
                        </a>
                        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                            <i class="material-symbols-rounded">person_add</i> Add User
                        </a>
                    @elseif(auth()->user()->isStationManager())
                        <a href="{{ route('fuel-requests.index') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">assignment</i> Review Requests
                        </a>
                        <a href="{{ route('receipts.upload') }}" class="btn btn-info btn-sm">
                            <i class="material-symbols-rounded">upload</i> Upload Receipt
                        </a>
                        <a href="{{ route('station.inventory') }}" class="btn btn-success btn-sm">
                            <i class="material-symbols-rounded">inventory</i> Check Inventory
                        </a>
                    @elseif(auth()->user()->isFuelPumper())
                        <a href="{{ route('fuel-requests.my-assignments') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">assignment</i> My Assignments
                        </a>
                        <a href="{{ route('fuel-requests.dispense') }}" class="btn btn-info btn-sm">
                            <i class="material-symbols-rounded">local_gas_station</i> Dispense Fuel
                        </a>
                        <a href="{{ route('reports.my-activity') }}" class="btn btn-success btn-sm">
                            <i class="material-symbols-rounded">analytics</i> My Activity
                        </a>
                    @elseif(auth()->user()->isTreasury())
                        <a href="{{ route('receipts.pending') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">receipt_long</i> Verify Receipts
                        </a>
                        <a href="{{ route('clients.overdue') }}" class="btn btn-warning btn-sm">
                            <i class="material-symbols-rounded">warning</i> Overdue Accounts
                        </a>
                        <a href="{{ route('reports.financial') }}" class="btn btn-success btn-sm">
                            <i class="material-symbols-rounded">analytics</i> Financial Reports
                        </a>
                    @else
                        <a href="{{ route('fuel-requests.create') }}" class="btn btn-primary btn-sm">
                            <i class="material-symbols-rounded">add</i> New Fuel Request
                        </a>
                        <a href="{{ route('clients.create') }}" class="btn btn-info btn-sm">
                            <i class="material-symbols-rounded">person_add</i> Add Client
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-success btn-sm">
                            <i class="material-symbols-rounded">analytics</i> View Reports
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
