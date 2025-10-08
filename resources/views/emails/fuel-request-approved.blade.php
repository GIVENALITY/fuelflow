@extends('emails.layout')

@section('title', 'Fuel Request Approved')
@section('header-title', 'Fuel Request Approved')
@section('header-subtitle', 'Your request has been approved and is ready for fulfillment')

@section('content')
    <div>
        <h2>Great news! Your fuel request has been approved.</h2>

        <p>Your fuel request for <strong>{{ $vehicle->plate_number }}</strong> has been approved and is now ready for
            fulfillment. Our team will begin processing your request shortly.</p>

        <div class="highlight-box">
            <h3>📋 Request Details</h3>
            <p>Here are the details of your approved fuel request:</p>
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
                <td>
                    <span class="status-badge status-approved">
                        {{ ucfirst($fuelRequest->fuel_type) }}
                    </span>
                </td>
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
                <th>Preferred Date</th>
                <td>{{ $fuelRequest->preferred_date ? $fuelRequest->preferred_date->format('M d, Y') : 'Not specified' }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td><strong>${{ number_format($fuelRequest->total_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge status-approved">
                        Approved
                    </span>
                </td>
            </tr>
        </table>

        @if($fuelRequest->special_instructions)
            <div class="highlight-box">
                <h3>📝 Special Instructions</h3>
                <p>{{ $fuelRequest->special_instructions }}</p>
            </div>
        @endif

        <div class="highlight-box">
            <h3>⏰ What's Next?</h3>
            <p>Your request has been assigned to our fulfillment team. You'll receive another notification when your fuel is
                ready for pickup or delivery.</p>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/fuel-requests/{{ $fuelRequest->id }}" class="button">
                View Request Details
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Need help?</strong> If you have any questions about this request, please contact our support team or
            reply to this email.
        </p>
    </div>
@endsection