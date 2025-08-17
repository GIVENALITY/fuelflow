@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Vehicle Details</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('vehicles.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Vehicles
                                </a>
                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-light me-2">
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
                                    <h6 class="mb-0">Vehicle Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Plate Number:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->plate_number }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Make:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->make }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Model:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->model }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Year:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->year }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Fuel Type:</strong>
                                        </div>
                                        <div class="col-8">
                                            <span class="badge badge-sm bg-gradient-{{ $vehicle->fuel_type === 'petrol' ? 'warning' : 'info' }}">
                                                {{ ucfirst($vehicle->fuel_type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-8">
                                            <span class="badge badge-sm bg-gradient-{{ $vehicle->status === 'active' ? 'success' : ($vehicle->status === 'maintenance' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($vehicle->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Fuel Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Tank Capacity:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->tank_capacity }} Liters
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Current Level:</strong>
                                        </div>
                                        <div class="col-8">
                                            <div class="d-flex align-items-center">
                                                <span class="text-sm font-weight-bold me-2">{{ $vehicle->current_fuel_level }}%</span>
                                                <div class="progress" style="width: 100px; height: 8px;">
                                                    <div class="progress-bar bg-gradient-{{ $vehicle->current_fuel_level < 20 ? 'danger' : ($vehicle->current_fuel_level < 50 ? 'warning' : 'success') }}" 
                                                         style="width: {{ $vehicle->current_fuel_level }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Available Fuel:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ number_format(($vehicle->tank_capacity * $vehicle->current_fuel_level) / 100, 1) }} Liters
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Client Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Client:</strong>
                                        </div>
                                        <div class="col-8">
                                            <a href="{{ route('clients.show', $vehicle->client) }}" class="text-decoration-none">
                                                {{ $vehicle->client->company_name }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Contact:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->client->contact_person }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Phone:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $vehicle->client->phone }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($vehicle->fuelRequests->count() > 0)
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
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Station</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($vehicle->fuelRequests->take(5) as $request)
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
                                                        <p class="text-xs font-weight-bold mb-0">{{ $request->station->name }}</p>
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
