<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FuelFlow Notification')</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 32px 24px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .header p {
            color: #e0e7ff;
            margin: 8px 0 0 0;
            font-size: 16px;
        }

        .content {
            padding: 32px 24px;
        }

        .content h2 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 16px 0;
        }

        .content p {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 16px 0;
        }

        .highlight-box {
            background-color: #f3f4f6;
            border-left: 4px solid #3b82f6;
            padding: 16px;
            margin: 24px 0;
            border-radius: 0 8px 8px 0;
        }

        .highlight-box h3 {
            color: #1f2937;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .highlight-box p {
            color: #4b5563;
            margin: 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .details-table th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 600;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .details-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .details-table tr:last-child td {
            border-bottom: none;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 16px 0;
            transition: all 0.2s;
        }

        .button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-1px);
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            color: #6b7280;
            font-size: 14px;
            margin: 0 0 8px 0;
        }

        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 24px 0;
        }

        .icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            vertical-align: middle;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }

            .content {
                padding: 24px 16px;
            }

            .header {
                padding: 24px 16px;
            }

            .details-table {
                font-size: 14px;
            }

            .details-table th,
            .details-table td {
                padding: 8px 12px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>@yield('header-title', 'FuelFlow')</h1>
            <p>@yield('header-subtitle', 'Fuel Management Platform')</p>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated message from FuelFlow. Please do not reply to this email.</p>
            <p>
                <a href="{{ config('app.url') }}">Visit FuelFlow</a> |
                <a href="{{ config('app.url') }}/profile">Manage Account</a>
            </p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>