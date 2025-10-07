@extends('layouts.app')

@section('title', 'Manage Fleet Owners & Users')

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
                                        <i class="fas fa-crown text-warning me-2"></i>Fleet Owners & System Users
                                    </h6>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-white-50">View Only - Manage users through Business Management</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            User</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Role</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Station</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Company</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Actions</th>
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
                                                <span class="badge badge-sm bg-gradient-{{ $user->role === 'super_admin' ? 'warning' : ($user->role === 'admin' ? 'danger' : ($user->role === 'station_manager' ? 'primary' : ($user->role === 'treasury' ? 'success' : 'info'))) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $user->station ? $user->station->name : 'N/A' }}
                                                </p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $user->client ? $user->client->company_name : 'N/A' }}
                                                </p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-link text-secondary mb-0">
                                                    <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-link text-secondary mb-0">
                                                    <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <p class="text-sm text-secondary mb-0">No users found.</p>
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
