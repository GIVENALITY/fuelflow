@extends('layouts.app')

@section('title')
    @if (Auth::user()->isSuperAdmin())
        Super Admin Dashboard
    @elseif (Auth::user()->isAdmin())
        Admin Dashboard
    @elseif (Auth::user()->isStationManager())
        Station Manager Dashboard
    @elseif (Auth::user()->isStationAttendant())
        Station Attendant Dashboard
    @elseif (Auth::user()->isTreasury())
        Treasury Dashboard
    @elseif (Auth::user()->isClient())
        Client Dashboard
    @else
        Dashboard
    @endif
@endsection

@section('content')
    <div class="container-fluid py-4">
        @if (Auth::user()->isSuperAdmin())
            @include('dashboard.super-admin')
        @elseif (Auth::user()->isAdmin())
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
@endsection