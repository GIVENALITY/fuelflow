@extends('layouts.app')

@section('title', 'Location Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Location Details</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('locations.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Locations
                                </a>
                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-light me-2">
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
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Location Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Name:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $location->name }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Type:</strong>
                                        </div>
                                        <div class="col-8">
                                            <span class="badge badge-sm bg-gradient-{{ $location->type === 'depot' ? 'primary' : ($location->type === 'station' ? 'success' : ($location->type === 'client_location' ? 'info' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $location->type)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-8">
                                            <span class="badge badge-sm bg-gradient-{{ $location->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($location->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($location->description)
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Description:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $location->description }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    @if($location->contact_person)
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Contact Person:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $location->contact_person }}
                                        </div>
                                    </div>
                                    @endif
                                    @if($location->phone)
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Phone:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $location->phone }}
                                        </div>
                                    </div>
                                    @endif
                                    @if($location->email)
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Email:</strong>
                                        </div>
                                        <div class="col-8">
                                            <a href="mailto:{{ $location->email }}">{{ $location->email }}</a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Address Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Full Address:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $location->full_address }}
                                        </div>
                                    </div>
                                    @if($location->coordinates)
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Coordinates:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $location->coordinates }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($location->routes->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Routes Using This Location</h6>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Route</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($location->routes as $route)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $route->name }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $route->formatted_distance }} • {{ $route->formatted_duration }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $route->startLocation->name }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $route->endLocation->name }}</p>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm bg-gradient-{{ $route->status === 'active' ? 'success' : ($route->status === 'draft' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($route->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('routes.show', $route) }}" class="btn btn-link text-secondary mb-0">
                                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($location->fuelRequests->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Recent Fuel Requests</h6>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Client</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($location->fuelRequests->take(5) as $request)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $request->created_at->format('M d, Y') }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $request->created_at->format('H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $request->client->company_name }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($request->amount, 2) }} TZS</p>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm bg-gradient-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($request->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
