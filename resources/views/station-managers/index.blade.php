@extends('layouts.app')

@section('title', 'Station Managers')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Station Managers</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('station-managers.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">add</i> Add Manager
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Manager</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($managers as $manager)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $manager->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $manager->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $manager->phone ?? 'N/A' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($manager->station)
                                            <span class="badge badge-sm bg-gradient-info">{{ $manager->station->name }}</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm {{ $manager->status === 'active' ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                            {{ ucfirst($manager->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('station-managers.show', $manager) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               data-bs-toggle="tooltip" 
                                               title="View Details">
                                                <i class="material-symbols-rounded">visibility</i>
                                            </a>
                                            <a href="{{ route('station-managers.edit', $manager) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="material-symbols-rounded">edit</i>
                                            </a>
                                            @if($manager->station)
                                                <form action="{{ route('station-managers.unassign', $manager) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to unassign this manager from their station?')">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-warning" 
                                                            data-bs-toggle="tooltip" 
                                                            title="Unassign Station">
                                                        <i class="material-symbols-rounded">person_remove</i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('station-managers.destroy', $manager) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this station manager?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Delete"
                                                        {{ $manager->station ? 'disabled' : '' }}>
                                                    <i class="material-symbols-rounded">delete</i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="material-symbols-rounded text-secondary" style="font-size: 48px;">person_off</i>
                                            <h6 class="text-secondary mt-2">No station managers found</h6>
                                            <p class="text-xs text-secondary">Create your first station manager to get started.</p>
                                            <a href="{{ route('station-managers.create') }}" class="btn btn-sm btn-primary mt-2">
                                                <i class="material-symbols-rounded me-1">add</i> Add Manager
                                            </a>
                                        </div>
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
