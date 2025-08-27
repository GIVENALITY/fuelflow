@extends('layouts.app')

@section('title', 'Preview Price Updates')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Preview Price Updates</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('bulk-price-updates.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Upload
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Update Summary</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Effective Date:</strong> {{ $effectiveDate->format('M d, Y') }}</p>
                                    <p class="mb-2"><strong>Total Updates:</strong> {{ count($updates) }}</p>
                                    <p class="mb-0"><strong>Price Changes:</strong> 
                                        @php
                                            $totalChanges = 0;
                                            foreach($updates as $update) {
                                                if ($update['petrol_update']) $totalChanges++;
                                                if ($update['diesel_update']) $totalChanges++;
                                            }
                                        @endphp
                                        {{ $totalChanges }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($errors))
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3 text-danger">Errors Found</h6>
                            <div class="card border-danger">
                                <div class="card-body">
                                    <ul class="mb-0">
                                        @foreach($errors as $error)
                                            <li class="text-sm text-danger">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(count($updates) > 0)
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Price Changes Preview</h6>
                            
                            <form action="{{ route('bulk-price-updates.apply') }}" method="POST">
                                @csrf
                                <input type="hidden" name="effective_date" value="{{ $effectiveDate->format('Y-m-d') }}">
                                
                                @foreach($updates as $index => $update)
                                    <input type="hidden" name="updates[{{ $index }}][station_id]" value="{{ $update['station_id'] }}">
                                    <input type="hidden" name="updates[{{ $index }}][station_code]" value="{{ $update['station_code'] }}">
                                    <input type="hidden" name="updates[{{ $index }}][station_name]" value="{{ $update['station_name'] }}">
                                    @if($update['petrol_update'])
                                        <input type="hidden" name="updates[{{ $index }}][petrol_update][new_price]" value="{{ $update['petrol_update']['new_price'] }}">
                                    @endif
                                    @if($update['diesel_update'])
                                        <input type="hidden" name="updates[{{ $index }}][diesel_update][new_price]" value="{{ $update['diesel_update']['new_price'] }}">
                                    @endif
                                    @if($update['kerosene_update'])
                                        <input type="hidden" name="updates[{{ $index }}][kerosene_update][new_price]" value="{{ $update['kerosene_update']['new_price'] }}">
                                    @endif
                                @endforeach

                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Station</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Fuel Type</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Price</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">New Price</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Change</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($updates as $update)
                                                @if($update['petrol_update'])
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $update['station_name'] }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $update['station_code'] }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm bg-gradient-info">Petrol</span>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">TZS {{ number_format($update['petrol_update']['current_price'], 2) }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0 text-success">TZS {{ number_format($update['petrol_update']['new_price'], 2) }}</p>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $change = $update['petrol_update']['change'];
                                                            $changeClass = $change > 0 ? 'text-success' : ($change < 0 ? 'text-danger' : 'text-secondary');
                                                            $changeIcon = $change > 0 ? '↗' : ($change < 0 ? '↘' : '→');
                                                        @endphp
                                                        <p class="text-xs font-weight-bold mb-0 {{ $changeClass }}">
                                                            {{ $changeIcon }} TZS {{ number_format(abs($change), 2) }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $update['town'] }}</p>
                                                    </td>
                                                </tr>
                                                @endif
                                                
                                                @if($update['diesel_update'])
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $update['station_name'] }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $update['station_code'] }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm bg-gradient-warning">Diesel</span>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">TZS {{ number_format($update['diesel_update']['current_price'], 2) }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0 text-success">TZS {{ number_format($update['diesel_update']['new_price'], 2) }}</p>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $change = $update['diesel_update']['change'];
                                                            $changeClass = $change > 0 ? 'text-success' : ($change < 0 ? 'text-danger' : 'text-secondary');
                                                            $changeIcon = $change > 0 ? '↗' : ($change < 0 ? '↘' : '→');
                                                        @endphp
                                                        <p class="text-xs font-weight-bold mb-0 {{ $changeClass }}">
                                                            {{ $changeIcon }} TZS {{ number_format(abs($change), 2) }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $update['town'] }}</p>
                                                    </td>
                                                </tr>
                                                @endif
                                                
                                                @if($update['kerosene_update'])
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $update['station_name'] }}</h6>
                                                                <p class="text-xs text-secondary mb-0">{{ $update['station_code'] }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-sm bg-gradient-secondary">Kerosene</span>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">TZS {{ number_format($update['kerosene_update']['current_price'], 2) }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0 text-success">TZS {{ number_format($update['kerosene_update']['new_price'], 2) }}</p>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $change = $update['kerosene_update']['change'];
                                                            $changeClass = $change > 0 ? 'text-success' : ($change < 0 ? 'text-danger' : 'text-secondary');
                                                            $changeIcon = $change > 0 ? '↗' : ($change < 0 ? '↘' : '→');
                                                        @endphp
                                                        <p class="text-xs font-weight-bold mb-0 {{ $changeClass }}">
                                                            {{ $changeIcon }} TZS {{ number_format(abs($change), 2) }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $update['town'] }}</p>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">
                                            <i class="material-symbols-rounded me-2">check</i>Apply All Changes
                                        </button>
                                        <a href="{{ route('bulk-price-updates.index') }}" class="btn btn-secondary">
                                            <i class="material-symbols-rounded me-2">cancel</i>Cancel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="material-symbols-rounded me-2">info</i>
                                No price changes found in the uploaded file. All prices are already up to date or no new prices were specified.
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
