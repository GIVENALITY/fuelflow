@extends('layouts.app')

@section('title', 'Corporate Clients')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Corporate Clients</h6>
                            </div>
                            @if(Auth::user()->isAdmin())
                            <div class="col-6 text-end">
                                <a href="{{ route('clients.create') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">add</i> Add Client
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Credit Limit</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Balance</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $client->company_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $client->contact_person }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $client->email }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $client->phone }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($client->credit_limit) }} TZS</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0 {{ $client->current_balance > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($client->current_balance) }} TZS
                                        </p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $client->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($client->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('clients.show', $client) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No clients found.</p>
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
