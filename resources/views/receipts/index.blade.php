@extends('layouts.app')

@section('title', 'Fuel Receipts')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Fuel Receipts</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('receipts.pending') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">pending</i> Pending Receipts
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Receipt</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Client</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($receipts as $receipt)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Receipt #{{ $receipt->id }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $receipt->station->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $receipt->client->name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $receipt->vehicle->plate_number }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ number_format($receipt->amount) }} TZS</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $receipt->status === 'verified' ? 'success' : ($receipt->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($receipt->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $receipt->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $receipt->created_at->format('H:i') }}</p>
                                    </td>
                                    <td>
                                        <a href="{{ route('receipts.show', $receipt) }}" class="btn btn-link text-secondary mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                        </a>
                                        @if(Auth::user()->isTreasury() && $receipt->status === 'pending')
                                        <a href="{{ route('receipts.verify', $receipt) }}" class="btn btn-link text-success mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">check</i>Verify
                                        </a>
                                        <a href="{{ route('receipts.reject', $receipt) }}" class="btn btn-link text-danger mb-0">
                                            <i class="material-symbols-rounded text-sm me-2">close</i>Reject
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-sm text-secondary mb-0">No receipts found.</p>
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
