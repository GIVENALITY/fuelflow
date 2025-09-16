@extends('emails.layout')

@section('title', 'Fuel Request Assigned')
@section('header-title', 'New Assignment')
@section('header-subtitle', 'You have been assigned a new fuel request')

@section('content')
    <div>
        <h2>You have a new fuel request assignment!</h2>

        <p>You have been assigned to fulfill fuel request <strong>#{{ $fuelRequest->id }}</strong> for
            <strong>{{ $vehicle->plate_number }}</strong>. Please review the details and begin the fulfillment process.</p>

        <div class="highlight-box">
            <h3>📋 Assignment Details</h3>
            <p>Here are the details of your new assignment:</p>
        </div>

        <table class="details-table">
            <tr>
                <th>Request ID</th>
                <td>#{{ $fuelRequest->id }}</td>
            </tr>
            <tr>
                <th>Client</th>
                <td>{{ $client->company_name }}</td>
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
                <th>Quantity to Dispense</th>
                <td><strong>{{ number_format($fuelRequest->quantity_requested, 2) }} L</strong></td>
            </tr>
            <tr>
                <th>Station</th>
                <td>{{ $station->name }}</td>
            </tr>
            <tr>
                <th>Preferred Date</th>
                <td>{{ $fuelRequest->preferred_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td><strong>${{ number_format($fuelRequest->total_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-badge status-pending">
                        Assigned to You
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
            <h3>⏰ Next Steps</h3>
            <p>Please follow these steps to complete the assignment:</p>
            <ol style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
                <li>Verify the vehicle and fuel type</li>
                <li>Dispense the requested quantity</li>
                <li>Record the actual quantity dispensed</li>
                <li>Mark the request as completed</li>
                <li>Upload receipt if required</li>
            </ol>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/fuel-requests/{{ $fuelRequest->id }}" class="button">
                View Assignment Details
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>Need help?</strong> If you have any questions about this assignment, please contact your station manager
            or support team.
        </p>
    </div>
@endsection