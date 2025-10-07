@extends('layouts.app')

@section('title', 'Manage Businesses')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0 font-weight-bold">Fuel Station Businesses</h6>
                                <p class="text-sm text-muted mb-0">Manage and monitor all business accounts</p>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('super-admin.businesses.create') }}" class="btn btn-sm btn-dark">
                                    <i class="fas fa-plus me-2"></i>Add Business
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Business</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Contact</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Stations</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Clients</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Contract</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($businesses as $business)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $business->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $business->business_code }} | {{ $business->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $business->contact_person }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $business->phone }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $business->status === 'approved' ? 'success' : ($business->status === 'pending' ? 'warning' : ($business->status === 'suspended' ? 'danger' : 'secondary')) }}">
                                                    {{ $business->status_display_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $business->stations_count }}</p>
                                                <p class="text-xs text-secondary mb-0">Stations</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $business->clients_count }}</p>
                                                <p class="text-xs text-secondary mb-0">Fleet Owners</p>
                                            </td>
                                            <td>
                                                @if($business->contract_signed)
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="fas fa-check me-1"></i>Signed
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-warning">
                                                        <i class="fas fa-clock me-1"></i>Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('super-admin.businesses.show', $business) }}" class="btn btn-link text-secondary mb-0">
                                                    <i class="fas fa-eye text-sm me-2"></i>View
                                                </a>
                                                <a href="{{ route('super-admin.businesses.edit', $business) }}" class="btn btn-link text-secondary mb-0">
                                                    <i class="fas fa-edit text-sm me-2"></i>Edit
                                                </a>
                                                @if($business->status === 'pending')
                                                    <form action="{{ route('super-admin.businesses.approve', $business) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-success mb-0">
                                                            <i class="fas fa-check text-sm me-2"></i>Approve
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($business->status === 'approved')
                                                    <form action="{{ route('super-admin.businesses.suspend', $business) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-warning mb-0">
                                                            <i class="fas fa-pause text-sm me-2"></i>Suspend
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($business->status === 'suspended')
                                                    <form action="{{ route('super-admin.businesses.activate', $business) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-success mb-0">
                                                            <i class="fas fa-play text-sm me-2"></i>Activate
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">No businesses found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
