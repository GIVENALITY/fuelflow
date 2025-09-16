@extends('layouts.app')

@section('title', 'My Assignments')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">My Assignments</h4>
                    <p class="text-sm text-secondary mb-0">Fuel requests assigned to you</p>
                </div>
                <div>
                    <a href="{{ route('fuel-requests.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="material-symbols-rounded me-2">arrow_back</i>Back to All Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>My Assignments ({{ $requests->total() }})</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if($requests->count() > 0)
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fuel Type</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">#{{ $request->id }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $request->client->company_name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $request->vehicle->plate_number ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $request->station->name ?? 'N/A' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ ucfirst($request->fuel_type) }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ number_format($request->quantity_requested, 2) }}L</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if($request->status === \App\Models\FuelRequest::STATUS_APPROVED)
                                                            <span class="badge badge-sm bg-gradient-success">Approved</span>
                                                        @elseif($request->status === \App\Models\FuelRequest::STATUS_IN_PROGRESS)
                                                            <span class="badge badge-sm bg-gradient-warning">In Progress</span>
                                                        @else
                                                            <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('fuel-requests.show', $request) }}" class="btn btn-info btn-sm">
                                                        <i class="material-symbols-rounded text-xs">visibility</i>
                                                    </a>
                                                    @if(in_array($request->status, [\App\Models\FuelRequest::STATUS_APPROVED, \App\Models\FuelRequest::STATUS_IN_PROGRESS]))
                                                        <form method="POST" action="{{ route('fuel-requests.dispense', $request) }}" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="quantity_dispensed" value="{{ $request->quantity_requested }}">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="material-symbols-rounded text-xs">local_gas_station</i>
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
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="material-symbols-rounded text-muted" style="font-size: 48px;">assignment</i>
                            <h6 class="text-muted mt-2">No assignments</h6>
                            <p class="text-sm text-muted">You don't have any fuel requests assigned to you.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
