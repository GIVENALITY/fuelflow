@extends('layouts.app')

@section('title', 'Pending Receipts')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Pending Receipt Verification</h6>
                                <p class="text-sm text-secondary mb-0">Review and verify receipts submitted by station staff
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="material-symbols-rounded me-2">arrow_back</i>Back to Receipts
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipts List -->
                <div class="card">
                    <div class="card-body">
                        @if($receipts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Receipt #</th>
                                            <th>Client</th>
                                            <th>Station</th>
                                            <th>Fuel Request</th>
                                            <th>Amount</th>
                                            <th>Uploaded By</th>
                                            <th>Uploaded At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($receipts as $receipt)
                                            <tr>
                                                <td>
                                                    <strong>{{ $receipt->receipt_number ?? 'N/A' }}</strong>
                                                </td>
                                                <td>{{ $receipt->client->company_name ?? 'N/A' }}</td>
                                                <td>{{ $receipt->station->name ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('fuel-requests.show', $receipt->fuel_request_id) }}"
                                                        class="text-primary">
                                                        #{{ $receipt->fuel_request_id }}
                                                    </a>
                                                </td>
                                                <td>TZS {{ number_format($receipt->amount, 2) }}</td>
                                                <td>{{ $receipt->uploadedBy->name ?? 'N/A' }}</td>
                                                <td>{{ $receipt->created_at->format('M d, Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ asset('storage/' . $receipt->file_path) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="material-symbols-rounded">visibility</i> View
                                                        </a>
                                                        <form action="{{ route('receipts.verify', $receipt) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Verify this receipt?')">
                                                                <i class="material-symbols-rounded">check</i> Verify
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('receipts.reject', $receipt) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Reject this receipt?')">
                                                                <i class="material-symbols-rounded">close</i> Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $receipts->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="material-symbols-rounded text-muted" style="font-size: 4rem;">receipt_long</i>
                                </div>
                                <h5 class="text-muted">No pending receipts</h5>
                                <p class="text-muted">All receipts have been verified.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection