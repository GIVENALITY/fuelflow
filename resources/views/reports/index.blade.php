@extends('layouts.app')

@section('title', 'Reports - FuelFlow')
@section('breadcrumb', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Reports Dashboard</h6>
                <p class="text-sm mb-0">
                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                    <span class="font-weight-bold ms-1">Reports coming soon</span>
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-gradient-info">
                            <div class="card-body text-center">
                                <h4 class="text-white">Operational Reports</h4>
                                <p class="text-white">Fuel request analytics and station performance</p>
                                <a href="{{ route('reports.operational') }}" class="btn btn-light btn-sm">View Reports</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-gradient-success">
                            <div class="card-body text-center">
                                <h4 class="text-white">Financial Reports</h4>
                                <p class="text-white">Revenue, payments, and financial analytics</p>
                                <a href="{{ route('reports.financial') }}" class="btn btn-light btn-sm">View Reports</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-gradient-warning">
                            <div class="card-body text-center">
                                <h4 class="text-white">Client Analytics</h4>
                                <p class="text-white">Client usage patterns and behavior analysis</p>
                                <a href="{{ route('reports.client-analytics') }}" class="btn btn-light btn-sm">View Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
