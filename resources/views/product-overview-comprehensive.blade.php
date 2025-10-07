<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FuelFlow - Complete Fuel Management Solution for PetroAfrica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 120px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .feature-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .feature-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .role-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .role-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: white;
        }
        .workflow-step {
            background: white;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-left: 6px solid #667eea;
            position: relative;
        }
        .workflow-step::before {
            content: '';
            position: absolute;
            top: 50%;
            right: -20px;
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: translateY(-50%);
        }
        .workflow-step:last-child::before {
            display: none;
        }
        .step-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .stats-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 100px 0;
        }
        .stat-item {
            text-align: center;
            padding: 40px 20px;
        }
        .stat-number {
            font-size: 4rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .section-title {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #2c3e50;
            text-align: center;
        }
        .section-subtitle {
            font-size: 1.3rem;
            color: #7f8c8d;
            margin-bottom: 60px;
            text-align: center;
        }
        .benefit-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .benefit-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2.5rem;
            backdrop-filter: blur(10px);
        }
        .demo-credentials {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 20px;
            padding: 40px;
            margin: 40px 0;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .cta-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }
        .cta-content {
            position: relative;
            z-index: 2;
        }
        .demo-button {
            background: white;
            color: #1e3c72;
            border: none;
            padding: 20px 50px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .demo-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            color: #1e3c72;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #28a745;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        .pricing-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .pricing-price {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-gas-pump me-2"></i>FuelFlow
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#workflow">Workflow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#roles">User Roles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#benefits">Benefits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#demo">Demo</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">FuelFlow</h1>
                    <h2 class="h3 mb-4">Complete Fuel Management Solution for PetroAfrica</h2>
                    <p class="lead mb-5">Transform PetroAfrica's fuel station operations with our comprehensive, cloud-based management platform. Streamline operations, enhance customer experience, maximize profitability, and scale your business with confidence.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="/register" class="btn btn-light btn-lg px-5 py-3">Start Free Trial</a>
                        <a href="#demo" class="btn btn-outline-light btn-lg px-5 py-3">View Live Demo</a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-5 py-3">Explore Features</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-gas-pump" style="font-size: 20rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <h4>Digital Operations</h4>
                        <p>Complete paperless fuel management system</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">75%</div>
                        <h4>Time Savings</h4>
                        <p>Reduced administrative overhead and manual processes</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <h4>Real-time Monitoring</h4>
                        <p>Live tracking, analytics, and automated reporting</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <h4>Uptime</h4>
                        <p>Enterprise-grade reliability and security</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="section-title">Comprehensive Feature Set</h2>
            <p class="section-subtitle">Everything you need to run a modern, efficient fuel station network</p>
            
            <div class="row g-4">
                <!-- Station Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-5">
                            <div class="feature-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h4 class="card-title mb-4">Multi-Station Management</h4>
                            <p class="card-text mb-4">Centralized control and monitoring of multiple fuel stations with real-time inventory tracking, performance analytics, and automated reporting.</p>
                            <ul class="feature-list text-start">
                                <li><i class="fas fa-check"></i>Centralized station dashboard</li>
                                <li><i class="fas fa-check"></i>Real-time inventory monitoring</li>
                                <li><i class="fas fa-check"></i>Performance analytics & KPIs</li>
                                <li><i class="fas fa-check"></i>Staff management & scheduling</li>
                                <li><i class="fas fa-check"></i>Equipment maintenance tracking</li>
                                <li><i class="fas fa-check"></i>Station-specific configurations</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Customer Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-5">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="card-title mb-4">Advanced Customer Management</h4>
                            <p class="card-text mb-4">Comprehensive customer lifecycle management from registration to ongoing relationship management with automated workflows.</p>
                            <ul class="feature-list text-start">
                                <li><i class="fas fa-check"></i>Streamlined registration process</li>
                                <li><i class="fas fa-check"></i>Document verification system</li>
                                <li><i class="fas fa-check"></i>Multi-vehicle fleet management</li>
                                <li><i class="fas fa-check"></i>Credit limit & payment controls</li>
                                <li><i class="fas fa-check"></i>Customer communication tools</li>
                                <li><i class="fas fa-check"></i>Loyalty program integration</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Order Processing -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-5">
                            <div class="feature-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4 class="card-title mb-4">Intelligent Order Processing</h4>
                            <p class="card-text mb-4">End-to-end order management from placement to completion with real-time tracking, automated workflows, and exception handling.</p>
                            <ul class="feature-list text-start">
                                <li><i class="fas fa-check"></i>Real-time order tracking</li>
                                <li><i class="fas fa-check"></i>Bulk order upload (CSV/Excel)</li>
                                <li><i class="fas fa-check"></i>Mobile order placement</li>
                                <li><i class="fas fa-check"></i>Automated workflow routing</li>
                                <li><i class="fas fa-check"></i>Exception handling & alerts</li>
                                <li><i class="fas fa-check"></i>Order history & analytics</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Payment Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-5">
                            <div class="feature-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h4 class="card-title mb-4">Secure Payment Processing</h4>
                            <p class="card-text mb-4">Comprehensive payment management with proof verification, automatic balance updates, and integrated financial reporting.</p>
                            <ul class="feature-list text-start">
                                <li><i class="fas fa-check"></i>Multi-payment method support</li>
                                <li><i class="fas fa-check"></i>Payment proof verification</li>
                                <li><i class="fas fa-check"></i>Automatic balance updates</li>
                                <li><i class="fas fa-check"></i>Financial reporting & analytics</li>
                                <li><i class="fas fa-check"></i>Audit trails & compliance</li>
                                <li><i class="fas fa-check"></i>Fraud detection & prevention</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Pricing Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-5">
                            <div class="feature-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h4 class="card-title mb-4">Dynamic Pricing Control</h4>
                            <p class="card-text mb-4">Advanced pricing management with station-specific rates, bulk updates, historical tracking, and competitive analysis.</p>
                            <ul class="feature-list text-start">
                                <li><i class="fas fa-check"></i>Dynamic pricing algorithms</li>
                                <li><i class="fas fa-check"></i>Bulk price updates</li>
                                <li><i class="fas fa-check"></i>Price history & analytics</li>
                                <li><i class="fas fa-check"></i>Station-specific pricing</li>
                                <li><i class="fas fa-check"></i>Competitive pricing analysis</li>
                                <li><i class="fas fa-check"></i>Automated price adjustments</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Analytics & Reporting -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card">
                        <div class="card-body text-center p-5">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="card-title mb-4">Advanced Analytics & Reporting</h4>
                            <p class="card-text mb-4">Comprehensive business intelligence with real-time dashboards, predictive analytics, and customizable reporting.</p>
                            <ul class="feature-list text-start">
                                <li><i class="fas fa-check"></i>Real-time dashboards</li>
                                <li><i class="fas fa-check"></i>Predictive analytics</li>
                                <li><i class="fas fa-check"></i>Custom report builder</li>
                                <li><i class="fas fa-check"></i>Performance metrics & KPIs</li>
                                <li><i class="fas fa-check"></i>Data export capabilities</li>
                                <li><i class="fas fa-check"></i>Automated report scheduling</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- User Roles Section -->
    <section id="roles" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Role-Based Access Control</h2>
            <p class="section-subtitle">Tailored interfaces and permissions for different user types</p>
            
            <div class="row g-4">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <div class="role-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h6 class="fw-bold">SuperAdmin</h6>
                        <small class="text-muted">Full system control</small>
                        <ul class="list-unstyled mt-3 text-start small">
                            <li>• System configuration</li>
                            <li>• User management</li>
                            <li>• Global settings</li>
                            <li>• Security controls</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <div class="role-icon" style="background: linear-gradient(135deg, #4834d4, #686de0);">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h6 class="fw-bold">Admin</h6>
                        <small class="text-muted">System management</small>
                        <ul class="list-unstyled mt-3 text-start small">
                            <li>• Client approval</li>
                            <li>• Station oversight</li>
                            <li>• Pricing management</li>
                            <li>• Report generation</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <div class="role-icon" style="background: linear-gradient(135deg, #00d2d3, #54a0ff);">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h6 class="fw-bold">Station Manager</h6>
                        <small class="text-muted">Station operations</small>
                        <ul class="list-unstyled mt-3 text-start small">
                            <li>• Staff management</li>
                            <li>• Order oversight</li>
                            <li>• Inventory control</li>
                            <li>• Performance monitoring</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <div class="role-icon" style="background: linear-gradient(135deg, #5f27cd, #00d2d3);">
                            <i class="fas fa-user-hard-hat"></i>
                        </div>
                        <h6 class="fw-bold">Station Attendant</h6>
                        <small class="text-muted">Fuel dispensing</small>
                        <ul class="list-unstyled mt-3 text-start small">
                            <li>• Order processing</li>
                            <li>• Fuel dispensing</li>
                            <li>• Receipt management</li>
                            <li>• Mobile operations</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <div class="role-icon" style="background: linear-gradient(135deg, #feca57, #ff9ff3);">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h6 class="fw-bold">Treasury</h6>
                        <small class="text-muted">Payment verification</small>
                        <ul class="list-unstyled mt-3 text-start small">
                            <li>• Payment verification</li>
                            <li>• Financial reporting</li>
                            <li>• Balance management</li>
                            <li>• Audit compliance</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <div class="role-icon" style="background: linear-gradient(135deg, #2ed573, #7bed9f);">
                            <i class="fas fa-building"></i>
                        </div>
                        <h6 class="fw-bold">Client</h6>
                        <small class="text-muted">Order placement</small>
                        <ul class="list-unstyled mt-3 text-start small">
                            <li>• Order placement</li>
                            <li>• Payment submission</li>
                            <li>• Fleet management</li>
                            <li>• Account monitoring</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Complete Workflow Section -->
    <section id="workflow" class="py-5">
        <div class="container">
            <h2 class="section-title">Complete Business Workflow</h2>
            <p class="section-subtitle">From customer onboarding to fuel delivery and payment processing</p>
            
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">1</div>
                        <h5>Customer Registration</h5>
                        <p>Quick signup with progressive profile completion, document uploads, and vehicle registration with automated validation.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Company information</li>
                            <li>• Document verification</li>
                            <li>• Vehicle registration</li>
                            <li>• Credit assessment</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">2</div>
                        <h5>Admin Approval</h5>
                        <p>Document verification, credit limit assignment, and customer activation by administrators with automated notifications.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Document review</li>
                            <li>• Credit limit setting</li>
                            <li>• Contract management</li>
                            <li>• Status activation</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">3</div>
                        <h5>Order Placement</h5>
                        <p>Customers place fuel orders with vehicle selection, quantity specification, and credit limit validation with real-time pricing.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Vehicle selection</li>
                            <li>• Quantity specification</li>
                            <li>• Credit validation</li>
                            <li>• Price calculation</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">4</div>
                        <h5>Station Assignment</h5>
                        <p>Station Manager assigns orders to attendants with real-time tracking and automated workflow routing.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Order assignment</li>
                            <li>• Attendant allocation</li>
                            <li>• Priority management</li>
                            <li>• Status tracking</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">5</div>
                        <h5>Fuel Dispensing</h5>
                        <p>Station Attendant dispenses fuel, records actual quantities, and uploads delivery documentation with mobile optimization.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Mobile interface</li>
                            <li>• Quantity recording</li>
                            <li>• Document upload</li>
                            <li>• Quality control</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">6</div>
                        <h5>Payment Processing</h5>
                        <p>Payment submission, verification by treasury team, and automatic balance updates with comprehensive audit trails.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Payment submission</li>
                            <li>• Proof verification</li>
                            <li>• Balance updates</li>
                            <li>• Audit compliance</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">7</div>
                        <h5>Receipt Generation</h5>
                        <p>Automatic receipt creation, document verification, and record keeping with digital archiving and compliance reporting.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Digital receipts</li>
                            <li>• Document archiving</li>
                            <li>• Compliance reporting</li>
                            <li>• Record management</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="step-number">8</div>
                        <h5>Analytics & Reporting</h5>
                        <p>Real-time analytics, performance metrics, and comprehensive business reports with predictive insights and recommendations.</p>
                        <ul class="list-unstyled text-start small">
                            <li>• Real-time analytics</li>
                            <li>• Performance metrics</li>
                            <li>• Predictive insights</li>
                            <li>• Business intelligence</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Benefits Section -->
    <section id="benefits" class="benefit-section">
        <div class="container">
            <h2 class="section-title text-white">Why Choose FuelFlow for PetroAfrica?</h2>
            <p class="section-subtitle text-white">Transform PetroAfrica's fuel station operations with measurable results</p>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="text-white">Operational Efficiency</h4>
                        <p class="text-white">Reduce administrative overhead by 75% with automated workflows, digital processes, and intelligent routing systems.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="text-white">Enterprise Security</h4>
                        <p class="text-white">Bank-level security with encrypted transactions, secure document storage, and comprehensive audit trails.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="text-white">Mobile-First Design</h4>
                        <p class="text-white">Access your system anywhere with responsive design, mobile-optimized interfaces, and offline capabilities.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="text-white">Data-Driven Insights</h4>
                        <p class="text-white">Make informed decisions with real-time dashboards, predictive analytics, and comprehensive business intelligence.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </div>
                        <h4 class="text-white">Scalable Architecture</h4>
                        <p class="text-white">Grow your business with a system that scales from single stations to large networks with cloud infrastructure.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="text-white">24/7 Support</h4>
                        <p class="text-white">Round-the-clock technical support, system maintenance, and dedicated account management for uninterrupted operations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="py-5">
        <div class="container">
            <h2 class="section-title">Live Demo & Testing</h2>
            <p class="section-subtitle">Experience the system with real data and complete workflows</p>
            
            <div class="demo-credentials">
                <h3><i class="fas fa-key me-2"></i>Demo Credentials</h3>
                <p class="mb-4">Use these credentials to test different user roles and explore all system features:</p>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Administrative Roles</h5>
                        <div class="mb-3">
                            <strong>SuperAdmin:</strong> superadmin@fuelflow.com / password<br>
                            <small class="text-muted">Full system control and configuration</small>
                        </div>
                        <div class="mb-3">
                            <strong>Admin:</strong> admin@fuelflow.com / password<br>
                            <small class="text-muted">System management and client approval</small>
                        </div>
                        <div class="mb-3">
                            <strong>Treasury:</strong> treasury@fuelflow.com / password<br>
                            <small class="text-muted">Payment verification and financial management</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Operational Roles</h5>
                        <div class="mb-3">
                            <strong>Station Manager:</strong> manager1@fuelflow.com / password<br>
                            <small class="text-muted">Station operations and staff management</small>
                        </div>
                        <div class="mb-3">
                            <strong>Station Attendant:</strong> attendant1@fuelflow.com / password<br>
                            <small class="text-muted">Fuel dispensing and order processing</small>
                        </div>
                        <div class="mb-3">
                            <strong>Client:</strong> client1@fuelflow.com / password<br>
                            <small class="text-muted">Order placement and account management</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h5 class="card-title">Customer Onboarding</h5>
                            <p class="card-text">Complete customer registration flow from signup to approval with document verification and vehicle management.</p>
                            <a href="/register" class="btn btn-primary">Test Registration</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h5 class="card-title">Order Processing</h5>
                            <p class="card-text">End-to-end order management from placement to completion with real-time tracking and mobile optimization.</p>
                            <a href="/login" class="btn btn-primary">Test Orders</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <h5 class="card-title">Analytics Dashboard</h5>
                            <p class="card-text">Comprehensive reporting and analytics with real-time dashboards and business intelligence insights.</p>
                            <a href="/login" class="btn btn-primary">View Analytics</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center cta-content">
            <h2 class="display-4 fw-bold mb-4">Ready to Transform PetroAfrica's Fuel Station Operations?</h2>
            <p class="lead mb-5">Partner with FuelFlow and experience the future of fuel management with measurable ROI and operational excellence</p>
            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <a href="/register" class="demo-button">Start Free Trial</a>
                <a href="/demo-guide" class="demo-button">View Demo Guide</a>
                <a href="/login" class="demo-button">Login to Demo</a>
            </div>
            <div class="mt-5">
                <p class="mb-0"><i class="fas fa-phone me-2"></i>Contact Sales: +255 XXX XXX XXX</p>
                <p class="mb-0"><i class="fas fa-envelope me-2"></i>Email: sales@fuelflow.com</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">FuelFlow for PetroAfrica</h5>
                    <p>Complete enterprise solution for PetroAfrica's fuel station operations. Streamline processes, enhance customer experience, and maximize profitability.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-white-50">Features</a></li>
                        <li><a href="#workflow" class="text-white-50">Workflow</a></li>
                        <li><a href="#roles" class="text-white-50">User Roles</a></li>
                        <li><a href="#demo" class="text-white-50">Demo</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Contact Information</h6>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>+255 XXX XXX XXX</p>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@fuelflow.com</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Dar es Salaam, Tanzania</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 FuelFlow. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Built with ❤️ for the fuel industry</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all feature cards and workflow steps
        document.querySelectorAll('.feature-card, .workflow-step, .role-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>
