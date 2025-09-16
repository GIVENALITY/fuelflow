@extends('emails.layout')

@section('title', 'Fuel Request Rejected')
@section('header-title', 'Fuel Request Rejected')
@section('header-subtitle', 'Your request could not be approved at this time')

@section('content')
    <div>
        <h2>We're sorry, but your fuel request has been rejected.</h2>

        <p>Unfortunately, your fuel request for <strong>{{ $vehicle->plate_number }}</strong> could not be approved at this
            time. Please review the details below and consider submitting a new request.</p>

        <div class="highlight-box" style="background-color: #fef2f2; border-left-color: #ef4444;">
            <h3 style="color: #991b1b;">❌ Rejection Reason</h3>
            <p style="color: #7f1d1d;">{{ $reason }}</p>
        </div>

        <table class="details-table">
            <tr>
                <th>Request ID</th>
                <td>#{{ $fuelRequest->id }}</td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>{{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->plate_number }})</td>
            </tr>
            <tr>
                <th>Fuel Type</th>
                <td>{{ ucfirst($fuelRequest->fuel_type) }}</td>
            </tr>
            <tr>
                <th>Quantity Requested</th>
                <td>{{ number_format($fuelRequest->quantity_requested, 2) }} L</td>
            </tr>
            <tr>
                <th>Station</th>
                <td>{{ $station->name }}</td>
            </tr>
            <tr>
                <th>Requested Date</th>
                <td>{{ $fuelRequest->preferred_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge status-rejected">
                        Rejected
                    </span>
                </td>
            </tr>
        </table>

        <div class="highlight-box">
            <h3>🔄 Next Steps</h3>
            <p>You can submit a new fuel request with corrected information. Please ensure that:</p>
            <ul style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
                <li>Your account is in good standing</li>
                <li>You have sufficient credit available</li>
                <li>The requested station has fuel available</li>
                <li>All vehicle information is accurate</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/fuel-requests/create" class="button">
                Submit New Request
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Need help?</strong> If you have questions about this rejection or need assistance with your account,
            please contact our support team.
        </p>
    </div>
@endsection