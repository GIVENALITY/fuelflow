<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetroAfrica Demo Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-gas-pump me-2"></i>PetroAfrica
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/product-overview">Product Overview</a>
                <a class="nav-link" href="/login">Login</a>
                <a class="nav-link" href="/register">Register</a>
            </div>
        </div>
    </nav>

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

        <!-- Phase 2: Customer Onboarding -->
        <div class="role-section">
            <h2><i class="fas fa-user-plus me-2"></i>Phase 2: Customer Onboarding</h2>
            <p>Test the complete customer registration and approval process</p>
        </div>

        <div class="demo-step">
            <div class="step-number">3</div>
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
            <div class="step-number">4</div>
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
            <div class="step-number">5</div>
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
            <div class="step-number">6</div>
            <h4>Station Processing</h4>
            <p>Station Manager and Attendant process the order:</p>
            <ul class="feature-list">
                <li><i class="fas fa-check"></i>Station Manager assigns orders to attendants</li>
                <li><i class="fas fa-check"></i>Station Attendant dispenses fuel</li>
                <li><i class="fas fa-check"></i>Upload delivery note and receipt images</li>
                <li><i class="fas fa-check"></i>Mark order as complete</li>
            </ul>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
