@extends('layouts.app')

@section('title', 'Upload Receipt')

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
                                    @if(auth()->user()->isStationManager())
                                        Upload Station Receipt
                                    @elseif(auth()->user()->isFuelPumper())
                                        Upload Assignment Receipt
                                    @else
                                        Upload Receipt
                                    @endif
                                </h6>
                                <p class="text-sm text-secondary mb-0">
                                    @if(auth()->user()->isStationManager())
                                        Upload a receipt for completed fuel requests at your station
                                    @elseif(auth()->user()->isFuelPumper())
                                        Upload a receipt for your assigned fuel request
                                    @else
                                        Upload a receipt for your completed fuel request
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back to Receipts
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <!-- Upload Form -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Receipt Information</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('receipts.store') }}" enctype="multipart/form-data"
                                    id="receiptForm">
                                    @csrf

                                    <!-- Fuel Request Selection -->
                                    <div class="mb-4">
                                        <label for="fuel_request_id" class="form-label">Select Fuel Request *</label>
                                        <select class="form-select @error('fuel_request_id') is-invalid @enderror"
                                            id="fuel_request_id" name="fuel_request_id" required>
                                            <option value="">Choose a fuel request...</option>
                                            @foreach($fuelRequests as $request)
                                                <option value="{{ $request->id }}"
                                                    data-vehicle="{{ $request->vehicle->plate_number }}"
                                                    data-station="{{ $request->station->name }}"
                                                    data-fuel-type="{{ $request->fuel_type }}"
                                                    data-quantity="{{ $request->quantity_dispensed }}"
                                                    data-amount="{{ $request->total_amount }}">
                                                    #{{ $request->id }} - {{ $request->vehicle->plate_number }}
                                                    ({{ $request->station->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('fuel_request_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Request Details Preview -->
                                    <div id="requestDetails" class="card bg-light mb-4" style="display: none;">
                                        <div class="card-body">
                                            <h6 class="card-title">Request Details</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Vehicle:</strong> <span
                                                            id="vehiclePlate">-</span></p>
                                                    <p class="mb-1"><strong>Station:</strong> <span
                                                            id="stationName">-</span></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Fuel Type:</strong> <span id="fuelType">-</span>
                                                    </p>
                                                    <p class="mb-1"><strong>Quantity:</strong> <span id="quantity">-</span>
                                                        L</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Receipt Photo Upload -->
                                    <div class="mb-4">
                                        <label for="receipt_photo" class="form-label">Receipt Photo *</label>
                                        <div class="upload-area" id="uploadArea">
                                            <div class="upload-content">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                <h5>Drag & Drop or Click to Upload</h5>
                                                <p class="text-muted">Upload a clear photo of your receipt (JPG, PNG, GIF -
                                                    Max 10MB)</p>
                                                <input type="file"
                                                    class="form-control @error('receipt_photo') is-invalid @enderror"
                                                    id="receipt_photo" name="receipt_photo" accept="image/*" required>
                                            </div>
                                            <div class="upload-preview" id="uploadPreview" style="display: none;">
                                                <img id="previewImage" src="" alt="Receipt Preview"
                                                    class="img-fluid rounded">
                                                <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                                    onclick="removeImage()">
                                                    <i class="fas fa-trash me-1"></i>
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                        @error('receipt_photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Amount and Quantity -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="amount" class="form-label">Amount ($) *</label>
                                                <input type="number"
                                                    class="form-control @error('amount') is-invalid @enderror" id="amount"
                                                    name="amount" step="0.01" min="0" required>
                                                @error('amount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantity (L) *</label>
                                                <input type="number"
                                                    class="form-control @error('quantity') is-invalid @enderror"
                                                    id="quantity" name="quantity" step="0.01" min="0" required>
                                                @error('quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fuel Type -->
                                    <div class="mb-3">
                                        <label for="fuel_type" class="form-label">Fuel Type *</label>
                                        <select class="form-select @error('fuel_type') is-invalid @enderror" id="fuel_type"
                                            name="fuel_type" required>
                                            <option value="">Select fuel type...</option>
                                            <option value="diesel">Diesel</option>
                                            <option value="petrol">Petrol</option>
                                        </select>
                                        @error('fuel_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <label for="notes" class="form-label">Additional Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes"
                                            name="notes" rows="3"
                                            placeholder="Any additional information about this receipt..."></textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('receipts.index') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload me-1"></i>
                                            Upload Receipt
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Upload Guidelines -->
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Upload Guidelines</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-primary">📸 Photo Requirements</h6>
                                    <ul class="list-unstyled text-sm">
                                        <li>• Clear, well-lit photo</li>
                                        <li>• All text should be readable</li>
                                        <li>• Receipt should be flat and unfolded</li>
                                        <li>• Avoid shadows and glare</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-primary">📋 Information Required</h6>
                                    <ul class="list-unstyled text-sm">
                                        <li>• Receipt number</li>
                                        <li>• Date and time</li>
                                        <li>• Station name</li>
                                        <li>• Fuel type and quantity</li>
                                        <li>• Total amount</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-primary">⏱️ Processing Time</h6>
                                    <p class="text-sm text-muted">
                                        Receipts are typically verified within 24-48 hours during business days.
                                    </p>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Tip:</strong> Make sure the receipt matches the selected fuel request details.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .upload-area.dragover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .upload-content {
            pointer-events: none;
        }

        .upload-preview img {
            max-height: 300px;
            object-fit: contain;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fuelRequestSelect = document.getElementById('fuel_request_id');
            const requestDetails = document.getElementById('requestDetails');
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('receipt_photo');
            const uploadPreview = document.getElementById('uploadPreview');
            const previewImage = document.getElementById('previewImage');

            // Handle fuel request selection
            fuelRequestSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];

                if (selectedOption.value) {
                    // Show request details
                    document.getElementById('vehiclePlate').textContent = selectedOption.dataset.vehicle;
                    document.getElementById('stationName').textContent = selectedOption.dataset.station;
                    document.getElementById('fuelType').textContent = selectedOption.dataset.fuelType;
                    document.getElementById('quantity').textContent = selectedOption.dataset.quantity;

                    // Pre-fill form fields
                    document.getElementById('amount').value = selectedOption.dataset.amount;
                    document.getElementById('quantity').value = selectedOption.dataset.quantity;
                    document.getElementById('fuel_type').value = selectedOption.dataset.fuelType;

                    requestDetails.style.display = 'block';
                } else {
                    requestDetails.style.display = 'none';
                }
            });

            // Handle file upload
            uploadArea.addEventListener('click', () => fileInput.click());

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            function handleFileSelect(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        uploadArea.querySelector('.upload-content').style.display = 'none';
                        uploadPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }

            window.removeImage = function () {
                fileInput.value = '';
                uploadArea.querySelector('.upload-content').style.display = 'block';
                uploadPreview.style.display = 'none';
            };
        });
    </script>
@endsection