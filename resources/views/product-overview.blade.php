@extends('layouts.product')

@section('title', 'PetroAfrica Fuel Management System - Complete Solution')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .workflow-step {
            position: relative;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .workflow-step::before {
            content: '';
            position: absolute;
            top: 50%;
            right: -20px;
            width: 40px;
            height: 2px;
            background: #667eea;
            transform: translateY(-50%);
        }
        .workflow-step:last-child::before {
            display: none;
        }
        .benefit-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        .role-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .role-card:hover {
            transform: translateY(-5px);
        }
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .demo-button {
            background: white;
            color: #667eea;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .demo-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: #667eea;
        }
        .stats-section {
            background: #f8f9fa;
            padding: 80px 0;
        }
        .stat-item {
            text-align: center;
            padding: 30px;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .section-subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 50px;
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">PetroAfrica Fuel Management System</h1>
                    <p class="lead mb-4">Complete end-to-end fuel management solution for modern fuel stations. Streamline operations, enhance customer experience, and maximize profitability.</p>
                    <div class="d-flex gap-3">
                        <a href="/register" class="btn btn-light btn-lg px-4">Start Free Trial</a>
                        <a href="{{ route('demo-guide') }}" class="btn btn-outline-light btn-lg px-4">Demo Guide</a>
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-gas-pump" style="font-size: 15rem; opacity: 0.3;"></i>
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
                        <h5>Digital Operations</h5>
                        <p>Complete paperless fuel management</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">50%</div>
                        <h5>Time Savings</h5>
                        <p>Reduced administrative overhead</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <h5>Real-time Monitoring</h5>
                        <p>Live tracking and analytics</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <h5>Uptime</h5>
                        <p>Reliable cloud-based system</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Complete Fuel Management Solution</h2>
                <p class="section-subtitle">Everything you need to run a modern, efficient fuel station</p>
            </div>
            
            <div class="row g-4">
                <!-- Station Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h4 class="card-title">Station Management</h4>
                            <p class="card-text">Centralized control of multiple fuel stations with real-time monitoring, inventory tracking, and performance analytics.</p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Multi-station dashboard</li>
                                <li><i class="fas fa-check text-success me-2"></i>Real-time inventory</li>
                                <li><i class="fas fa-check text-success me-2"></i>Performance analytics</li>
                                <li><i class="fas fa-check text-success me-2"></i>Staff management</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Customer Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="card-title">Customer Management</h4>
                            <p class="card-text">Streamlined customer onboarding with document verification, vehicle management, and credit limit controls.</p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Quick registration</li>
                                <li><i class="fas fa-check text-success me-2"></i>Document verification</li>
                                <li><i class="fas fa-check text-success me-2"></i>Vehicle management</li>
                                <li><i class="fas fa-check text-success me-2"></i>Credit controls</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Order Processing -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4 class="card-title">Order Processing</h4>
                            <p class="card-text">Efficient order management from placement to completion with real-time tracking and automated workflows.</p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Real-time tracking</li>
                                <li><i class="fas fa-check text-success me-2"></i>Bulk order upload</li>
                                <li><i class="fas fa-check text-success me-2"></i>Mobile dispensing</li>
                                <li><i class="fas fa-check text-success me-2"></i>Automated workflows</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Payment Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h4 class="card-title">Payment Management</h4>
                            <p class="card-text">Secure payment processing with proof verification, automatic balance updates, and comprehensive financial tracking.</p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Payment verification</li>
                                <li><i class="fas fa-check text-success me-2"></i>Balance automation</li>
                                <li><i class="fas fa-check text-success me-2"></i>Financial reports</li>
                                <li><i class="fas fa-check text-success me-2"></i>Audit trails</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Pricing Management -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h4 class="card-title">Pricing Management</h4>
                            <p class="card-text">Dynamic pricing control with station-specific rates, bulk updates, and historical price tracking.</p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Dynamic pricing</li>
                                <li><i class="fas fa-check text-success me-2"></i>Bulk updates</li>
                                <li><i class="fas fa-check text-success me-2"></i>Price history</li>
                                <li><i class="fas fa-check text-success me-2"></i>Station-specific rates</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Reporting & Analytics -->
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="card-title">Reporting & Analytics</h4>
                            <p class="card-text">Comprehensive reporting with real-time dashboards, performance metrics, and business intelligence insights.</p>
                            <ul class="list-unstyled text-start">
                                <li><i class="fas fa-check text-success me-2"></i>Real-time dashboards</li>
                                <li><i class="fas fa-check text-success me-2"></i>Performance metrics</li>
                                <li><i class="fas fa-check text-success me-2"></i>Business intelligence</li>
                                <li><i class="fas fa-check text-success me-2"></i>Custom reports</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- User Roles Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Role-Based Access Control</h2>
                <p class="section-subtitle">Tailored interfaces for different user types</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <i class="fas fa-crown text-warning mb-3" style="font-size: 2rem;"></i>
                        <h6>SuperAdmin</h6>
                        <small class="text-muted">Full system control</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <i class="fas fa-user-shield text-primary mb-3" style="font-size: 2rem;"></i>
                        <h6>Admin</h6>
                        <small class="text-muted">System management</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <i class="fas fa-user-tie text-info mb-3" style="font-size: 2rem;"></i>
                        <h6>Station Manager</h6>
                        <small class="text-muted">Station operations</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <i class="fas fa-user-hard-hat text-success mb-3" style="font-size: 2rem;"></i>
                        <h6>Station Attendant</h6>
                        <small class="text-muted">Fuel dispensing</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <i class="fas fa-coins text-warning mb-3" style="font-size: 2rem;"></i>
                        <h6>Treasury</h6>
                        <small class="text-muted">Payment verification</small>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="role-card">
                        <i class="fas fa-building text-secondary mb-3" style="font-size: 2rem;"></i>
                        <h6>Client</h6>
                        <small class="text-muted">Order placement</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Complete Workflow</h2>
                <p class="section-subtitle">From customer registration to fuel delivery</p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h5>1. Customer Registration</h5>
                        <p>Quick signup with progressive profile completion, document uploads, and vehicle registration.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5>2. Admin Approval</h5>
                        <p>Document verification, credit limit assignment, and customer activation by administrators.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5>3. Order Placement</h5>
                        <p>Customers place fuel orders with vehicle selection, quantity, and credit limit validation.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-gas-pump"></i>
                        </div>
                        <h5>4. Fuel Dispensing</h5>
                        <p>Station attendants dispense fuel, upload receipts, and complete order processing.</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h5>5. Payment Processing</h5>
                        <p>Payment submission, verification by treasury, and automatic balance updates.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h5>6. Receipt Generation</h5>
                        <p>Automatic receipt creation, document verification, and record keeping.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5>7. Reporting</h5>
                        <p>Real-time analytics, performance metrics, and comprehensive business reports.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="workflow-step text-center">
                        <div class="benefit-icon mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h5>8. Continuous Monitoring</h5>
                        <p>Ongoing system monitoring, performance optimization, and process improvements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Why Choose PetroAfrica System?</h2>
                <p class="section-subtitle">Transform your fuel station operations</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Save Time</h4>
                        <p>Reduce administrative overhead by 50% with automated workflows and digital processes.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Enhanced Security</h4>
                        <p>Secure document storage, encrypted transactions, and comprehensive audit trails.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>Mobile Ready</h4>
                        <p>Access your system anywhere with responsive design and mobile-optimized interfaces.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Real-time Insights</h4>
                        <p>Make data-driven decisions with live dashboards and comprehensive analytics.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h4>Scalable Solution</h4>
                        <p>Grow your business with a system that scales from single stations to large networks.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="text-center">
                        <div class="benefit-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>24/7 Support</h4>
                        <p>Round-the-clock technical support and system maintenance for uninterrupted operations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Scenarios Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Demo Scenarios</h2>
                <p class="section-subtitle">See the system in action</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user-plus text-primary me-2"></i>Customer Onboarding</h5>
                            <p class="card-text">Complete customer registration flow from signup to approval.</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-arrow-right text-primary me-2"></i>Quick registration</li>
                                <li><i class="fas fa-arrow-right text-primary me-2"></i>Profile completion</li>
                                <li><i class="fas fa-arrow-right text-primary me-2"></i>Vehicle management</li>
                                <li><i class="fas fa-arrow-right text-primary me-2"></i>Admin approval</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-shopping-cart text-success me-2"></i>Order Processing</h5>
                            <p class="card-text">End-to-end order management from placement to completion.</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-arrow-right text-success me-2"></i>Order placement</li>
                                <li><i class="fas fa-arrow-right text-success me-2"></i>Station assignment</li>
                                <li><i class="fas fa-arrow-right text-success me-2"></i>Fuel dispensing</li>
                                <li><i class="fas fa-arrow-right text-success me-2"></i>Receipt generation</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-tags text-warning me-2"></i>Price Management</h5>
                            <p class="card-text">Dynamic pricing updates across multiple stations.</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-arrow-right text-warning me-2"></i>Price updates</li>
                                <li><i class="fas fa-arrow-right text-warning me-2"></i>Bulk changes</li>
                                <li><i class="fas fa-arrow-right text-warning me-2"></i>Historical tracking</li>
                                <li><i class="fas fa-arrow-right text-warning me-2"></i>Real-time sync</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Fuel Station?</h2>
            <p class="lead mb-5">Join PetroAfrica and experience the future of fuel management</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/register" class="demo-button">Start Free Trial</a>
                <a href="{{ route('demo-guide') }}" class="demo-button">View Demo Guide</a>
                <a href="/login" class="demo-button">Login to Demo</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>PetroAfrica Fuel Management System</h5>
                    <p class="mb-0">Complete fuel station management solution</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 PetroAfrica. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

@endsection
