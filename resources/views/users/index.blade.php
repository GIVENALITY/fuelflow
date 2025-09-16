@extends('layouts.app')

@section('title', Auth::user()->isStationManager() ? 'Station Staff' : 'System Users')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="text-white text-capitalize ps-3">
                                        {{ Auth::user()->isStationManager() ? 'Station Staff' : 'System Users' }}</h6>
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <div class="col-6 text-end">
                                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-light me-3">
                                            <i class="material-symbols-rounded">add</i> Add User
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{ Auth::user()->isStationManager() ? 'Staff Member' : 'User' }}</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Role</th>
                                        @if(Auth::user()->isAdmin())
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Station</th>
                                        @endif
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status</th>
                                        @if(Auth::user()->isAdmin())
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'station_manager' ? 'primary' : ($user->role === 'treasury' ? 'success' : 'info')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                                </span>
                                            </td>
                                            @if(Auth::user()->isAdmin())
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $user->station ? $user->station->name : 'N/A' }}
                                                    </p>
                                                </td>
                                            @endif
                                            <td>
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            @if(Auth::user()->isAdmin())
                                                <td>
                                                    <a href="{{ route('users.show', $user) }}"
                                                        class="btn btn-link text-secondary mb-0">
                                                        <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                                    </a>
                                                    @if(Auth::user()->isAdmin())
                                                        <a href="{{ route('users.edit', $user) }}"
                                                            class="btn btn-link text-secondary mb-0">
                                                            <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ Auth::user()->isAdmin() ? '5' : '3' }}" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">
                                                    {{ Auth::user()->isStationManager() ? 'No staff members found.' : 'No users found.' }}
                                                </p>
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