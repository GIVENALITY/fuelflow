@extends('emails.layout')

@section('title', 'Low Stock Alert')
@section('header-title', 'Low Stock Alert')
@section('header-subtitle', 'Fuel levels are running low at your station')

@section('content')
    <div>
        <h2>Low stock alert for {{ $station->name }}</h2>

        <p>The {{ ucfirst($fuelType) }} fuel levels at <strong>{{ $station->name }}</strong> have dropped below the
            recommended threshold. Please take immediate action to restock.</p>

        <div class="highlight-box" style="background-color: #fef2f2; border-left-color: #ef4444;">
            <h3 style="color: #991b1b;">⚠️ Stock Alert</h3>
            <p style="color: #7f1d1d;">{{ ucfirst($fuelType) }} fuel levels are critically low and require immediate
                attention.</p>
        </div>

        <table class="details-table">
            <tr>
                <th>Station</th>
                <td><strong>{{ $station->name }}</strong></td>
            </tr>
            <tr>
                <th>Fuel Type</th>
                <td>
                    <span class="status-badge status-rejected">
                        {{ ucfirst($fuelType) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Current Level</th>
                <td><strong>{{ number_format($currentLevel, 2) }} L</strong></td>
            </tr>
            <tr>
                <th>Low Stock Threshold</th>
                <td>{{ number_format($threshold, 2) }} L</td>
            </tr>
            <tr>
                <th>Station Capacity</th>
                <td>{{ number_format($station->getCurrentFuelLevel($fuelType), 2) }} L</td>
            </tr>
            <tr>
                <th>Stock Level</th>
                <td>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div style="flex: 1; background-color: #e5e7eb; border-radius: 4px; height: 8px;">
                            <div
                                style="background-color: #ef4444; height: 100%; width: {{ $percentage }}%; border-radius: 4px;">
                            </div>
                        </div>
                        <span style="font-weight: 600; color: #991b1b;">{{ $percentage }}%</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="highlight-box">
            <h3>🚨 Immediate Actions Required</h3>
            <p>Please take the following actions immediately:</p>
            <ul style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
                <li>Contact your fuel supplier to schedule an urgent delivery</li>
                <li>Check if any scheduled deliveries are delayed</li>
                <li>Consider temporarily limiting fuel sales to essential requests only</li>
                <li>Update station status if necessary</li>
            </ul>
        </div>

        <div class="highlight-box">
            <h3>📞 Emergency Contacts</h3>
            <p>If you need immediate assistance with fuel supply:</p>
            <ul style="margin: 8px 0; padding-left: 20px; color: #4b5563;">
                <li>Primary Supplier: [Contact Information]</li>
                <li>Backup Supplier: [Contact Information]</li>
                <li>Regional Manager: [Contact Information]</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 32px 0;">
            <a href="{{ config('app.url') }}/stations/{{ $station->id }}/inventory" class="button">
                View Station Inventory
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size: 14px; color: #6b7280;">
            <strong>This is an automated alert.</strong> Please take immediate action to prevent service disruption. Contact
            support if you need assistance.
        </p>
    </div>
@endsection