@extends('layouts.app')

@section('title', 'Route Details')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-white text-capitalize ps-3">Route Details</h6>
                            </div>
                            <div class="col-6 text-end">
                                <a href="{{ route('routes.index') }}" class="btn btn-sm btn-light me-3">
                                    <i class="material-symbols-rounded">arrow_back</i> Back to Routes
                                </a>
                                @if(Auth::user()->isAdmin())
                                <a href="{{ route('routes.edit', $route) }}" class="btn btn-sm btn-light me-2">
                                    <i class="material-symbols-rounded">edit</i> Edit
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Route Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Name:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $route->name }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-8">
                                            <span class="badge badge-sm bg-gradient-{{ $route->status === 'active' ? 'success' : ($route->status === 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($route->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Distance:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $route->formatted_distance }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Duration:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $route->formatted_duration }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Route Endpoints</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>Start:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $route->startLocation->name }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-4">
                                            <strong>End:</strong>
                                        </div>
                                        <div class="col-8">
                                            {{ $route->endLocation->name }}
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
</div>
@endsection
