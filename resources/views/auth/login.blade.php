@extends('layouts.auth')

@section('title', 'Login - FuelFlow')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="card">
                    <div class="card-header p-3">
                        <div class="text-center">
                            <h4 class="font-weight-bolder mb-0">Sign in to FuelFlow</h4>
                            <p class="text-sm text-secondary mb-0">Enter your credentials to access the system</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form role="form" method="POST" action="{{ route('login') }}" class="text-start">
                            @csrf
                            <div class="input-group input-group-outline {{ old('email') ? 'is-filled' : '' }} my-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <p class="text-danger text-xs pt-1">{{ $message }}</p>
                            @enderror

                            <div class="input-group input-group-outline {{ old('password') ? 'is-filled' : '' }} mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="form-check form-switch d-flex align-items-center mb-3">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                                <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer p-3">
                        <div class="text-center">
                            <p class="text-sm text-secondary mb-0">
                                <strong>Test Accounts:</strong>
                            </p>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="card bg-gradient-info">
                                        <div class="card-body p-2">
                                            <small class="text-white">
                                                <strong>Admin:</strong> admin@fuelflow.co.tz / password<br>
                                                <strong>Manager:</strong> manager@fuelflow.co.tz / password<br>
                                                <strong>Pumper:</strong> pumper@fuelflow.co.tz / password<br>
                                                <strong>Treasury:</strong> treasury@fuelflow.co.tz / password<br>
                                                <strong>Client:</strong> client@fuelflow.co.tz / password
                                            </small>
                                        </div>
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