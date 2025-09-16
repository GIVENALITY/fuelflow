@extends('layouts.app')

@section('title', 'Fuel Price Details')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="text-white text-capitalize ps-3">Fuel Price Details</h6>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('fuel-prices.index') }}" class="btn btn-sm btn-light me-3">
                                        <i class="material-symbols-rounded">arrow_back</i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Station</label>
                                    <p class="form-control-plaintext">{{ $fuelPrice->station->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Fuel Type</label>
                                    <p class="form-control-plaintext">
                                        <span
                                            class="badge badge-sm bg-gradient-{{ $fuelPrice->fuel_type === 'petrol' ? 'warning' : 'info' }}">
                                            {{ ucfirst($fuelPrice->fuel_type) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Price (TZS)</label>
                                    <p class="form-control-plaintext font-weight-bold">
                                        {{ number_format($fuelPrice->price, 2) }} TZS</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Effective Date</label>
                                    <p class="form-control-plaintext">{{ $fuelPrice->effective_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @if($fuelPrice->notes)
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Notes</label>
                                        <p class="form-control-plaintext">{{ $fuelPrice->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Created</label>
                                    <p class="form-control-plaintext">{{ $fuelPrice->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection