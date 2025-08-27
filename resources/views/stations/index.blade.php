@extends('layouts.app')

@section('title', 'Fuel Stations')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Fuel Stations</h6>
                            </div>
                            @if(Auth::user()->isAdmin())
                            <div class="col-6 text-end">
                                <a href="{{ route('stations.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">add</i> Add Station
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Code</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Manager</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stations as $station)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $station->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $station->code }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            @if($station->location)
                                                {{ $station->location->name }}<br>
                                                <small class="text-muted">{{ $station->location->full_address }}</small>
                                            @else
                                                <span class="text-muted">No location assigned</span>
                                            @endif
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $station->manager ? $station->manager->name : 'No Manager' }}
                                        </p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $station->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($station->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('stations.show', $station) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('stations.edit', $station) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No stations found.</p>
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
