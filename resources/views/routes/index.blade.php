@extends('layouts.app')

@section('title', 'Routes')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Routes</h6>
                            </div>
                            @if(Auth::user()->isAdmin())
                            <div class="col-6 text-end">
                                <a href="{{ route('routes.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">add</i> Add Route
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Route</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Distance</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Duration</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($routes as $route)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $route->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $route->stops_count }} stops</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $route->startLocation->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $route->startLocation->city }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $route->endLocation->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $route->endLocation->city }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $route->formatted_distance }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $route->formatted_duration }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $route->status === 'active' ? 'success' : ($route->status === 'draft' ? 'warning' : ($route->status === 'maintenance' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($route->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('routes.show', $route) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('routes.edit', $route) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No routes found.</p>
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
