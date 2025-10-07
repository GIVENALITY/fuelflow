@extends('layouts.product')

@section('title', 'PetroAfrica Demo Guide - Step by Step Instructions')

@push('styles')
    <style>
        .demo-step {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
        }
        .step-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .demo-credentials {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .role-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #28a745;
            margin-right: 10px;
        }
    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-4">PetroAfrica Demo Guide</h1>
        <p class="lead">Complete step-by-step instructions to explore all system features</p>
    </div>

    <!-- Demo Credentials -->
    <div class="demo-credentials">
        <h3><i class="fas fa-key me-2"></i>Demo Credentials</h3>
        <p>Use these credentials to test different user roles:</p>
        <div class="row">
            <div class="col-md-6">
                <strong>SuperAdmin:</strong> superadmin@fuelflow.com / password<br>
                <strong>Admin:</strong> admin@fuelflow.com / password<br>
                <strong>Treasury:</strong> treasury@fuelflow.com / password
            </div>
            <div class="col-md-6">
                <strong>Station Manager:</strong> manager1@fuelflow.com / password<br>
                <strong>Station Attendant:</strong> attendant1@fuelflow.com / password<br>
                <strong>Client:</strong> client1@fuelflow.com / password
            </div>
        </div>
    </div>

    <!-- Phase 1: System Setup -->
    <div class="role-section">
        <h2><i class="fas fa-cog me-2"></i>Phase 1: System Setup (SuperAdmin)</h2>
        <p>Start with SuperAdmin to set up the system infrastructure</p>
    </div>

    <div class="demo-step">
        <div class="step-number">1</div>
        <h4>Login as SuperAdmin</h4>
        <p>Navigate to <a href="/login" class="btn btn-primary btn-sm">Login Page</a> and use:</p>
        <ul>
            <li><strong>Email:</strong> superadmin@fuelflow.com</li>
            <li><strong>Password:</strong> password</li>
        </ul>
        <p>You'll see the SuperAdmin dashboard with system-wide controls.</p>
    </div>

    <div class="demo-step">
        <div class="step-number">2</div>
        <h4>Manage Stations</h4>
        <p>From the SuperAdmin dashboard:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Click "Manage Stations" to view all fuel stations</li>
            <li><i class="fas fa-check"></i>Add new stations with location details</li>
            <li><i class="fas fa-check"></i>Assign Station Managers to each station</li>
            <li><i class="fas fa-check"></i>Configure available fuel types per station</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">3</div>
        <h4>User Management</h4>
        <p>Create and manage system users:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Navigate to "User Management"</li>
            <li><i class="fas fa-check"></i>Create new users with specific roles</li>
            <li><i class="fas fa-check"></i>Assign Station Managers to stations</li>
            <li><i class="fas fa-check"></i>Set user permissions and access levels</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">4</div>
        <h4>Set Fuel Prices</h4>
        <p>Configure pricing for all stations:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Go to "Fuel Prices" section</li>
            <li><i class="fas fa-check"></i>Set prices for different fuel types</li>
            <li><i class="fas fa-check"></i>Apply station-specific pricing</li>
            <li><i class="fas fa-check"></i>Schedule price changes for future dates</li>
        </ul>
    </div>

    <!-- Phase 2: Customer Onboarding -->
    <div class="role-section">
        <h2><i class="fas fa-user-plus me-2"></i>Phase 2: Customer Onboarding</h2>
        <p>Test the complete customer registration and approval process</p>
    </div>

    <div class="demo-step">
        <div class="step-number">5</div>
        <h4>Customer Registration</h4>
        <p>Test the customer registration flow:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Go to <a href="/register" class="btn btn-success btn-sm">Registration Page</a></li>
            <li><i class="fas fa-check"></i>Enter company name, email, and password</li>
            <li><i class="fas fa-check"></i>Complete profile with company details</li>
            <li><i class="fas fa-check"></i>Upload required documents (TIN, BRELA, etc.)</li>
            <li><i class="fas fa-check"></i>Add vehicles with documents</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">6</div>
        <h4>Admin Approval Process</h4>
        <p>Switch to Admin role to approve customers:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Login as admin@fuelflow.com</li>
            <li><i class="fas fa-check"></i>Go to "Client Management"</li>
            <li><i class="fas fa-check"></i>Review pending applications</li>
            <li><i class="fas fa-check"></i>Verify uploaded documents</li>
            <li><i class="fas fa-check"></i>Set credit limits and approve customers</li>
        </ul>
    </div>

    <!-- Phase 3: Daily Operations -->
    <div class="role-section">
        <h2><i class="fas fa-shopping-cart me-2"></i>Phase 3: Daily Operations</h2>
        <p>Experience the complete order-to-delivery workflow</p>
    </div>

    <div class="demo-step">
        <div class="step-number">7</div>
        <h4>Customer Places Order</h4>
        <p>Login as a client to place fuel orders:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Login as client1@fuelflow.com</li>
            <li><i class="fas fa-check"></i>Navigate to "Place Order"</li>
            <li><i class="fas fa-check"></i>Select vehicle from dropdown</li>
            <li><i class="fas fa-check"></i>Enter fuel amount in litres</li>
            <li><i class="fas fa-check"></i>Submit order (credit limit validation)</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">8</div>
        <h4>Station Manager Assignment</h4>
        <p>Station Manager processes the order:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Login as manager1@fuelflow.com</li>
            <li><i class="fas fa-check"></i>View active orders in dashboard</li>
            <li><i class="fas fa-check"></i>Assign orders to station attendants</li>
            <li><i class="fas fa-check"></i>Monitor order progress</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">9</div>
        <h4>Fuel Dispensing</h4>
        <p>Station Attendant dispenses fuel:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Login as attendant1@fuelflow.com</li>
            <li><i class="fas fa-check"></i>Search for vehicle by plate number</li>
            <li><i class="fas fa-check"></i>View order details and requirements</li>
            <li><i class="fas fa-check"></i>Record actual litres dispensed</li>
            <li><i class="fas fa-check"></i>Upload delivery note and receipt images</li>
            <li><i class="fas fa-check"></i>Mark order as complete</li>
        </ul>
    </div>

    <!-- Phase 4: Payment Processing -->
    <div class="role-section">
        <h2><i class="fas fa-credit-card me-2"></i>Phase 4: Payment Processing</h2>
        <p>Complete the payment verification workflow</p>
    </div>

    <div class="demo-step">
        <div class="step-number">10</div>
        <h4>Payment Submission</h4>
        <p>Client submits payment proof:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Login as client1@fuelflow.com</li>
            <li><i class="fas fa-check"></i>Go to "Payments" section</li>
            <li><i class="fas fa-check"></i>Upload payment proof (bank receipt)</li>
            <li><i class="fas fa-check"></i>Enter bank details and amount</li>
            <li><i class="fas fa-check"></i>Submit for verification</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">11</div>
        <h4>Treasury Verification</h4>
        <p>Treasury team verifies payments:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Login as treasury@fuelflow.com</li>
            <li><i class="fas fa-check"></i>View pending payments</li>
            <li><i class="fas fa-check"></i>Review payment proofs</li>
            <li><i class="fas fa-check"></i>Approve or reject payments</li>
            <li><i class="fas fa-check"></i>System automatically updates client balance</li>
        </ul>
    </div>

    <!-- Phase 5: Reporting & Analytics -->
    <div class="role-section">
        <h2><i class="fas fa-chart-line me-2"></i>Phase 5: Reporting & Analytics</h2>
        <p>Explore comprehensive reporting features</p>
    </div>

    <div class="demo-step">
        <div class="step-number">12</div>
        <h4>Dashboard Analytics</h4>
        <p>View real-time system metrics:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Each role has customized dashboard</li>
            <li><i class="fas fa-check"></i>Real-time order tracking</li>
            <li><i class="fas fa-check"></i>Revenue and volume analytics</li>
            <li><i class="fas fa-check"></i>Performance metrics by station</li>
        </ul>
    </div>

    <div class="demo-step">
        <div class="step-number">13</div>
        <h4>Generate Reports</h4>
        <p>Create detailed business reports:</p>
        <ul class="feature-list">
            <li><i class="fas fa-check"></i>Sales reports by station and fuel type</li>
            <li><i class="fas fa-check"></i>Customer performance analytics</li>
            <li><i class="fas fa-check"></i>Payment and credit reports</li>
            <li><i class="fas fa-check"></i>Export data for external analysis</li>
        </ul>
    </div>

    <!-- Key Features to Test -->
    <div class="role-section">
        <h2><i class="fas fa-star me-2"></i>Key Features to Test</h2>
        <p>Don't miss these important system capabilities</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="demo-step">
                <h5><i class="fas fa-upload me-2"></i>Bulk Operations</h5>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i>Bulk order upload (CSV/Excel)</li>
                    <li><i class="fas fa-check"></i>Bulk price updates</li>
                    <li><i class="fas fa-check"></i>Template downloads</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="demo-step">
                <h5><i class="fas fa-mobile-alt me-2"></i>Mobile Features</h5>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i>Responsive design</li>
                    <li><i class="fas fa-check"></i>Mobile order placement</li>
                    <li><i class="fas fa-check"></i>Photo upload for receipts</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="demo-step">
                <h5><i class="fas fa-bell me-2"></i>Notifications</h5>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i>Real-time notifications</li>
                    <li><i class="fas fa-check"></i>Email alerts</li>
                    <li><i class="fas fa-check"></i>Status updates</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="demo-step">
                <h5><i class="fas fa-shield-alt me-2"></i>Security Features</h5>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i>Role-based access control</li>
                    <li><i class="fas fa-check"></i>Document verification</li>
                    <li><i class="fas fa-check"></i>Audit trails</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="text-center mt-5">
        <h3>Ready to Start?</h3>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="/login" class="btn btn-primary btn-lg">Login to Demo</a>
            <a href="/register" class="btn btn-success btn-lg">Register New Customer</a>
            <a href="/product-overview" class="btn btn-outline-primary btn-lg">Back to Overview</a>
        </div>
    </div>
</div>
@endsection
