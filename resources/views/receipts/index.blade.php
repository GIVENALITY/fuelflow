@extends('layouts.app')

@section('title', 'Receipts')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">
                                @if(auth()->user()->isClient())
                                    My Receipts
                                @elseif(auth()->user()->isStationManager())
                                    Station Receipts
                                @elseif(auth()->user()->isStationAttendant())
                                    My Assigned Receipts
                                @elseif(auth()->user()->isTreasury())
                                    All Receipts for Verification
                                @elseif(auth()->user()->isAdmin())
                                    System Receipts
                                @else
                                    Receipts
                                @endif
                            </h6>
                            <p class="text-sm text-secondary mb-0">
                                @if(auth()->user()->isClient())
                                    View and track your fuel receipts
                                @elseif(auth()->user()->isStationManager())
                                    Manage receipts for your station
                                @elseif(auth()->user()->isStationAttendant())
                                    View receipts for your assigned requests
                                @elseif(auth()->user()->isTreasury())
                                    Verify and manage all system receipts
                                @elseif(auth()->user()->isAdmin())
                                    Monitor all system receipts
                                @else
                                    Manage and track fuel receipts
                                @endif
                            </p>
                        </div>
                        @if(auth()->user()->isStationManager() || auth()->user()->isStationAttendant())
                            <a href="{{ route('receipts.create') }}" class="btn btn-primary btn-sm">
                                <i class="material-symbols-rounded me-1">add</i>
                                Upload Receipt
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('receipts.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fuel_type" class="form-label">Fuel Type</label>
                            <select class="form-select" id="fuel_type" name="fuel_type">
                                <option value="">All Types</option>
                                <option value="diesel" {{ request('fuel_type') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="petrol" {{ request('fuel_type') === 'petrol' ? 'selected' : '' }}>Petrol</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>
                                Apply Filters
                            </button>
                            <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Receipts Table -->
            <div class="card">
                <div class="card-body p-0">
                    @if($receipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Receipt</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vehicle</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receipts as $receipt)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($receipt->file_path)
                                                            <img src="{{ $receipt->file_url }}" 
                                                                 alt="Receipt" 
                                                                 class="img-thumbnail" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        @endif
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                             style="width: 50px; height: 50px; {{ $receipt->file_path ? 'display: none;' : '' }}">
                                                            <i class="fas fa-receipt text-muted"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm">{{ $receipt->receipt_number }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $receipt->fuel_type }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-bold">#{{ $receipt->fuel_request_id }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-car text-primary me-2"></i>
                                                    <span class="text-sm">{{ $receipt->fuelRequest->vehicle->plate_number ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm">{{ $receipt->station->name }}</span>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-bold">TZS {{ number_format($receipt->amount, 0) }}</span>
                                                <br>
                                                <small class="text-secondary">{{ number_format($receipt->quantity, 2) }}L</small>
                                            </td>
                                            <td>
                                                @switch($receipt->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('verified')
                                                        <span class="badge bg-success">Verified</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="text-sm">{{ $receipt->created_at->format('M d, Y') }}</span>
                                                <br>
                                                <small class="text-secondary">{{ $receipt->created_at->format('g:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('receipts.show', $receipt) }}" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                        <span class="d-none d-md-inline ms-1">View</span>
                                                    </a>
                                                    @if($receipt->file_path)
                                                        <a href="{{ route('receipts.download', $receipt) }}" 
                                                           class="btn btn-outline-secondary btn-sm"
                                                           title="Download Receipt">
                                                            <i class="fas fa-download"></i>
                                                            <span class="d-none d-md-inline ms-1">Download</span>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->canVerifyReceipts() && $receipt->status === 'pending')
                                                        <div class="btn-group" role="group">
                                                            <button type="button" 
                                                                    class="btn btn-outline-success btn-sm dropdown-toggle" 
                                                                    data-bs-toggle="dropdown"
                                                                    title="Verify/Reject Receipt">
                                                                <i class="fas fa-check"></i>
                                                                <span class="d-none d-md-inline ms-1">Verify</span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <button class="dropdown-item" 
                                                                            onclick="verifyReceipt({{ $receipt->id }}, 'verify')">
                                                                        <i class="fas fa-check me-2"></i>
                                                                        Verify Receipt
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item text-danger" 
                                                                            onclick="verifyReceipt({{ $receipt->id }}, 'reject')">
                                                                        <i class="fas fa-times me-2"></i>
                                                                        Reject Receipt
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $receipts->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-receipt text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">No receipts found</h5>
                            <p class="text-muted">
                                @if(auth()->user()->isClient())
                                    Upload your first receipt to get started.
                                @else
                                    No receipts match your current filters.
                                @endif
                            </p>
                            @if(auth()->user()->isClient())
                                <a href="{{ route('receipts.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Upload Receipt
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalTitle">Verify Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="verificationForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="verificationAction" name="action">
                    <div class="mb-3">
                        <label for="verificationNotes" class="form-label">Notes</label>
                        <textarea class="form-control" 
                                  id="verificationNotes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Add any notes about this verification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="verificationSubmitBtn">Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function verifyReceipt(receiptId, action) {
    const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
    const form = document.getElementById('verificationForm');
    const title = document.getElementById('verificationModalTitle');
    const submitBtn = document.getElementById('verificationSubmitBtn');
    const actionInput = document.getElementById('verificationAction');
    
    actionInput.value = action;
    form.action = `/receipts/${receiptId}/verify`;
    
    if (action === 'verify') {
        title.textContent = 'Verify Receipt';
        submitBtn.textContent = 'Verify';
        submitBtn.className = 'btn btn-success';
    } else {
        title.textContent = 'Reject Receipt';
        submitBtn.textContent = 'Reject';
        submitBtn.className = 'btn btn-danger';
    }
    
    modal.show();
}
</script>
@endsection