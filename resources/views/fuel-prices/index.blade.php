@extends('layouts.app')

@section('title', 'Fuel Prices')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="text-white text-capitalize ps-3">Fuel Prices</h6>
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <div class="col-6 text-end">
                                        <a href="{{ route('fuel-prices.create') }}" class="btn btn-sm btn-light me-3">
                                            <i class="material-symbols-rounded">add</i> Add Price
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
                                            Station</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Fuel Type</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Price (TZS)</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Effective Date</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fuelPrices as $fuelPrice)
                                                        <tr>
                                                            </p>
                                            </div>
                                        </div>
                                        </td>

                                        <td>
                                            <span
                                                class="badge badge-sm bg-gradient-{{ $fuelPrice->fuel_type === 'petrol' ? 'warning' : 'info' }}">
                                                {{ ucfirst($fuelPrice->fuel_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ number_format($fuelPrice->price, 2) }} TZS
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $fuelPrice->effective_date->format('M d, Y') }}
                                            </p>
                                        </td>
                                        <td>
                                            <a href="{{ route('fuel-prices.show', $fuelPrice) }}" class="btn btn-link text-secondary mb-0">
                                                <i class="material-symbols-rounded text-sm me-2">visibility</i>View
                                            </a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('fuel-prices.edit', $fuelPrice) }}" class="btn btn-link text-secondary mb-0">
                                                    <i class="material-symbols-rounded text-sm me-2">edit</i>Edit
                                                </a>
                                            @endif
                                        </td>
                                        </tr>
                                    @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection