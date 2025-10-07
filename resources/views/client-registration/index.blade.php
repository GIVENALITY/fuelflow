<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration - FuelFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Client Registration</h3>
                        <p class="mb-0">Register your company to start using FuelFlow services</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('client-registration.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Company Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2"><i class="fas fa-building me-2"></i>Company Information</h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">Company Name *</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_person" class="form-label">Contact Person *</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="address" class="form-label">Address *</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State/Region *</label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="zip_code" class="form-label">ZIP Code *</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country *</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="Tanzania" {{ old('country') == 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                                        <option value="Kenya" {{ old('country') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                        <option value="Uganda" {{ old('country') == 'Uganda' ? 'selected' : '' }}>Uganda</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="tax_id" class="form-label">Tax ID (TIN) *</label>
                                    <input type="text" class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id') }}" required>
                                </div>
                            </div>

                            <!-- Document Uploads -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Required Documents</h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tin_document" class="form-label">TIN Document *</label>
                                    <input type="file" class="form-control" id="tin_document" name="tin_document" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="brela_certificate" class="form-label">BRELA Certificate *</label>
                                    <input type="file" class="form-control" id="brela_certificate" name="brela_certificate" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="business_license" class="form-label">Business License *</label>
                                    <input type="file" class="form-control" id="business_license" name="business_license" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="director_id" class="form-label">Director ID *</label>
                                    <input type="file" class="form-control" id="director_id" name="director_id" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                </div>
                            </div>

                            <!-- Vehicle Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2"><i class="fas fa-truck me-2"></i>Vehicle Information</h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="vehicle_plate_number" class="form-label">Vehicle Plate Number *</label>
                                    <input type="text" class="form-control" id="vehicle_plate_number" name="vehicle_plate_number" value="{{ old('vehicle_plate_number') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="vehicle_type" class="form-label">Vehicle Type *</label>
                                    <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                                        <option value="">Select Vehicle Type</option>
                                        <option value="truck" {{ old('vehicle_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                                        <option value="tractor" {{ old('vehicle_type') == 'tractor' ? 'selected' : '' }}>Tractor</option>
                                        <option value="trailer" {{ old('vehicle_type') == 'trailer' ? 'selected' : '' }}>Trailer</option>
                                        <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="bus" {{ old('vehicle_type') == 'bus' ? 'selected' : '' }}>Bus</option>
                                        <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="vehicle_make" class="form-label">Make *</label>
                                    <input type="text" class="form-control" id="vehicle_make" name="vehicle_make" value="{{ old('vehicle_make') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="vehicle_model" class="form-label">Model *</label>
                                    <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="vehicle_year" class="form-label">Year *</label>
                                    <input type="number" class="form-control" id="vehicle_year" name="vehicle_year" value="{{ old('vehicle_year') }}" min="1900" max="{{ date('Y') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="vehicle_fuel_type" class="form-label">Fuel Type *</label>
                                    <select class="form-select" id="vehicle_fuel_type" name="vehicle_fuel_type" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="diesel" {{ old('vehicle_fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="petrol" {{ old('vehicle_fuel_type') == 'petrol' ? 'selected' : '' }}>Gasoline</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="head_card" class="form-label">Kadi ya Kichwa (Head Card) *</label>
                                    <input type="file" class="form-control" id="head_card" name="head_card" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB)</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="trailer_card" class="form-label">Kadi ya Trailer (Trailer Card)</label>
                                    <input type="file" class="form-control" id="trailer_card" name="trailer_card" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">PDF, JPG, PNG (Max 2MB) - Optional</small>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary border-bottom pb-2"><i class="fas fa-user-lock me-2"></i>Account Information</h5>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Note:</strong> Your registration will be reviewed by our team. You will be notified via email once your application is approved.
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-paper-plane me-2"></i>Submit Registration
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
