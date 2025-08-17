@extends('layouts.app')

@section('title', 'Locations')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Locations</h6>
                            </div>
                            @if(Auth::user()->isAdmin())
                            <div class="col-6 text-end">
                                <a href="{{ route('locations.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">add</i> Add Location
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Address</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Routes</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $location->name }}</h6>
                                                @if($location->contact_person)
                                                <p class="text-xs text-secondary mb-0">{{ $location->contact_person }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $location->type === 'depot' ? 'primary' : ($location->type === 'station' ? 'success' : ($location->type === 'client_location' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $location->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $location->full_address }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $location->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($location->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $location->routes->count() }} routes</p>
                                    </td>
                                    <td>
                                        <a href="{{ route('locations.show', $location) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('locations.edit', $location) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No locations found.</p>
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
