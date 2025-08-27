@extends('layouts.app')

@section('title', 'Bulk Price Updates')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Bulk Price Updates</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('bulk-price-updates.download-template') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">download</i> Download Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Upload Excel File</h6>
                            
                            <form action="{{ route('bulk-price-updates.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>CSV File *</label>
                                            <input type="file" class="form-control @error('csv_file') is-invalid @enderror" 
                                                   name="csv_file" accept=".csv,.txt" required>
                                            @error('csv_file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Upload CSV file (.csv or .txt) with price updates</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-static mb-4">
                                            <label>Effective Date *</label>
                                            <input type="date" class="form-control @error('effective_date') is-invalid @enderror" 
                                                   name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}" required>
                                            @error('effective_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="preview_mode" value="1" id="previewMode" checked>
                                            <label class="form-check-label" for="previewMode">
                                                Preview mode (show changes before applying)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-symbols-rounded me-2">upload</i>Upload and Process
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Instructions</h6>
                                </div>
                                <div class="card-body">
                                    <ol class="mb-0">
                                        <li class="mb-2">Download the CSV template</li>
                                        <li class="mb-2">Fill in the new prices in the price columns</li>
                                        <li class="mb-2">Set the effective date for price changes</li>
                                        <li class="mb-2">Upload the completed CSV file</li>
                                        <li class="mb-2">Review the preview and apply changes</li>
                                    </ol>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <strong>Note:</strong> Only stations with new prices will be updated. 
                                            Leave cells empty to keep current prices unchanged.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Template Information</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-2"><strong>Template Format:</strong> The CSV template contains the following columns:</p>
                                    <ul class="mb-3">
                                        <li><strong>S/N:</strong> Serial number for each town</li>
                                        <li><strong>Town:</strong> Town name (e.g., Dar es Salaam, Arusha)</li>
                                        <li><strong>Cap Prices:</strong> Current cap prices for the town</li>
                                        <li><strong>Petrol:</strong> Petrol price in TZS</li>
                                        <li><strong>Diesel:</strong> Diesel price in TZS</li>
                                        <li><strong>Kerosene:</strong> Kerosene price in TZS</li>
                                    </ul>
                                    
                                    <p class="mb-2"><strong>How it works:</strong></p>
                                    <ol class="mb-0">
                                        <li>Download the CSV template with current prices</li>
                                        <li>Update the prices for towns you want to change</li>
                                        <li>Upload the CSV file - system will find stations in each town</li>
                                        <li>Review and apply the changes</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
