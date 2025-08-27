@extends('layouts.app')

@section('title', 'Station Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Station Details</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('stations.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Stations
                                </a>
                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('stations.edit', $station) }}" class="btn btn-sm btn-light me-2">
                                    <i class="material-symbols-rounded">edit</i> Edit
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Basic Information</h6>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Station Name</label>
                                            <p class="form-control-static">{{ $station->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Location</label>
                                            <p class="form-control-static">
                                                @if($station->location)
                                                    <strong>{{ $station->location->name }}</strong><br>
                                                    {{ $station->location->full_address }}
                                                @else
                                                    <span class="text-muted">No location assigned</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Status</label>
                                            <span class="badge badge-sm bg-gradient-{{ $station->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($station->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Management</h6>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Manager</label>
                                            <p class="form-control-static">
                                                @if($station->manager)
                                                    {{ $station->manager->name }} ({{ $station->manager->email }})
                                                @else
                                                    <span class="text-muted">No manager assigned</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Created</label>
                                            <p class="form-control-static">{{ $station->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Last Updated</label>
                                            <p class="form-control-static">{{ $station->updated_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($station->fuelRequests->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Recent Fuel Requests</h6>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Client</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Vehicle</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fuel Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($station->fuelRequests->take(5) as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">#{{ $request->id }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->client->company_name ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->vehicle->plate_number ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ ucfirst($request->fuel_type) }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->quantity }} L</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($station->receipts->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Recent Receipts</h6>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Receipt ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Client</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($station->receipts->take(5) as $receipt)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">#{{ $receipt->id }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $receipt->client->company_name ?? 'N/A' }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">TZS {{ number_format($receipt->amount, 2) }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $receipt->status === 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($receipt->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $receipt->created_at->format('M d, Y') }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
