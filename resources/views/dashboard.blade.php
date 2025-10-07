@if (Auth::user()->isSuperAdmin())
    @extends('layouts.app')
    @section('title', 'Super Admin Dashboard')
    @section('content')
        <div class="container-fluid py-4">
            @include('dashboard.super-admin')
        </div>
    @endsection
@else
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard - FuelFlow</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-gas-pump me-2"></i>FuelFlow
                </a>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text me-3">
                        Welcome, {{ Auth::user()->name }} ({{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }})
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            @if (Auth::user()->isAdmin())
                @include('dashboard.admin')
            @elseif (Auth::user()->isStationManager())
                @include('dashboard.station-manager')
            @elseif (Auth::user()->isStationAttendant())
                @include('dashboard.station-attendant')
            @elseif (Auth::user()->isTreasury())
                @include('dashboard.treasury')
            @elseif (Auth::user()->isClient())
                @include('dashboard.client')
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Your account is being set up. Please contact the administrator.
                </div>
            @endif
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
@endif