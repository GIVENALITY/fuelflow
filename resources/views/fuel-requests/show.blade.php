@extends('layouts.app')

@section('title', 'Fuel Request Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Fuel Request #{{ $fuelRequest->id }}</h6>
                        <a href="{{ route('fuel-requests.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-secondary text-xs">Client</label>
                                <div class="text-sm">{{ $fuelRequest->client->company_name ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-secondary text-xs">Vehicle</label>
                                <div class="text-sm">{{ $fuelRequest->vehicle->plate_number ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-secondary text-xs">Station</label>
                                <div class="text-sm">{{ $fuelRequest->station->name ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-secondary text-xs">Fuel Type</label>
                                <div class="text-sm">{{ ucfirst($fuelRequest->fuel_type) }}</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="text-secondary text-xs">Quantity Requested (L)</label>
                                <div class="text-sm">{{ number_format($fuelRequest->quantity_requested, 2) }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-xs">Quantity Dispensed (L)</label>
                                <div class="text-sm">{{ number_format($fuelRequest->quantity_dispensed ?? 0, 2) }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-xs">Total Amount</label>
                                <div class="text-sm">${{ number_format($fuelRequest->total_amount ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="text-secondary text-xs">Status</label>
                                <div class="text-sm text-capitalize">{{ str_replace('_', ' ', $fuelRequest->status) }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-xs">Requested At</label>
                                <div class="text-sm">{{ optional($fuelRequest->request_date)->format('M d, Y') }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-xs">Preferred Date</label>
                                <div class="text-sm">{{ optional($fuelRequest->preferred_date)->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @if($fuelRequest->special_instructions)
                            <div class="mb-3">
                                <label class="text-secondary text-xs">Special Instructions</label>
                                <div class="text-sm">{{ $fuelRequest->special_instructions }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if(auth()->user()->isClient() && $fuelRequest->status === \App\Models\FuelRequest::STATUS_PENDING && $fuelRequest->client_id === auth()->user()->client?->id)
                                <form method="POST" action="{{ route('fuel-requests.destroy', $fuelRequest) }}"
                                    onsubmit="return confirm('Cancel this request?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Cancel Request</button>
                                </form>
                            @endif

                            @if(auth()->user()->isStationManager() && $fuelRequest->status === \App\Models\FuelRequest::STATUS_PENDING)
                                <form method="POST" action="{{ route('fuel-requests.approve', $fuelRequest) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm" type="submit">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('fuel-requests.reject', $fuelRequest) }}">
                                    @csrf
                                    <input type="hidden" name="rejection_reason" value="Insufficient stock">
                                    <button class="btn btn-outline-danger btn-sm" type="submit">Reject</button>
                                </form>
                            @endif

                            @if(auth()->user()->isStationManager() && $fuelRequest->status === \App\Models\FuelRequest::STATUS_APPROVED)
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="text-sm text-muted">Assign to:</span>
                                    <form method="POST" action="{{ route('fuel-requests.assign', $fuelRequest) }}" class="d-inline">
                                        @csrf
                                        <select name="pumper_id" class="form-select form-select-sm" style="width: auto;" required>
                                            <option value="">Select Fuel Pumper</option>
                                            @foreach(\App\Models\User::where('station_id', auth()->user()->station_id)->where('role', 'fuel_pumper')->get() as $pumper)
                                                <option value="{{ $pumper->id }}">{{ $pumper->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary btn-sm ms-2" type="submit">
                                            <i class="material-symbols-rounded text-xs">person_add</i> Assign
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if(auth()->user()->isFuelPumper() && in_array($fuelRequest->status, [\App\Models\FuelRequest::STATUS_APPROVED, \App\Models\FuelRequest::STATUS_IN_PROGRESS]))
                                <form method="POST" action="{{ route('fuel-requests.dispense', $fuelRequest) }}">
                                    @csrf
                                    <input type="hidden" name="quantity_dispensed"
                                        value="{{ $fuelRequest->quantity_requested }}">
                                    <button class="btn btn-primary btn-sm" type="submit">Mark as Dispensed</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header pb-0">
                        <h6>Status Timeline</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Requested</strong>
                                <div class="text-secondary text-xs">
                                    {{ optional($fuelRequest->request_date)->format('M d, Y \a\t g:i A') }}</div>
                            </li>
                            @if($fuelRequest->approved_at)
                                <li class="mb-2">
                                    <strong>Approved</strong>
                                    <div class="text-secondary text-xs">
                                        {{ optional($fuelRequest->approved_at)->format('M d, Y \a\t g:i A') }}</div>
                                </li>
                            @endif
                            @if($fuelRequest->dispensed_at)
                                <li class="mb-2">
                                    <strong>Dispensed</strong>
                                    <div class="text-secondary text-xs">
                                        {{ optional($fuelRequest->dispensed_at)->format('M d, Y \a\t g:i A') }}</div>
                                </li>
                            @endif
                            @if($fuelRequest->status === \App\Models\FuelRequest::STATUS_COMPLETED)
                                <li class="mb-2">
                                    <strong>Completed</strong>
                                    <div class="text-secondary text-xs">
                                        {{ optional($fuelRequest->updated_at)->format('M d, Y \a\t g:i A') }}</div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection