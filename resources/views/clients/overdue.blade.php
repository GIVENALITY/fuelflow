@extends('layouts.app')

@section('title', 'Overdue Accounts')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-warning shadow-warning border-radius-lg pt-4 pb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="text-white text-capitalize ps-3">Overdue Accounts</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <span class="badge badge-sm bg-white text-warning me-3">
                                        {{ $clients->count() }} Overdue
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        @if($clients->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Client</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Company</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Overdue Amount</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Credit Limit</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Status</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($clients as $client)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $client->user->name ?? 'N/A' }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $client->user->email ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $client->company_name }}</p>
                                                </td>
                                                <td>
                                                    <span class="badge badge-sm bg-gradient-danger">
                                                        {{ number_format($client->current_balance, 2) }} TZS
                                                    </span>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ number_format($client->credit_limit, 2) }} TZS</p>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-sm bg-gradient-{{ $client->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($client->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('clients.show', $client) }}"
                                                        class="btn btn-link text-secondary mb-0">
                                                        <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                                    </a>
                                                    @if(Auth::user()->isTreasury() || Auth::user()->isAdmin())
                                                        <a href="{{ route('payments.create', ['client_id' => $client->id]) }}"
                                                            class="btn btn-link text-success mb-0">
                                                            <i class="material-symbols-rounded text-sm me-2">payment</i>Record Payment
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="material-symbols-rounded text-success" style="font-size: 4rem;">check_circle</i>
                                <h5 class="text-success mt-3">No Overdue Accounts</h5>
                                <p class="text-secondary">All clients are up to date with their payments.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection