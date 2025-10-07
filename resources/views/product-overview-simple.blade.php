<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetroAfrica Fuel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a class="nav-link" href="/login">Login</a>
                <a class="nav-link" href="/register">Register</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">PetroAfrica Fuel Management System</h1>
                    <p class="lead mb-4">Complete end-to-end fuel management solution for modern fuel stations. Streamline operations, enhance customer experience, and maximize profitability.</p>
                    <div class="d-flex gap-3">
                        <a href="/register" class="btn btn-light btn-lg px-4">Start Free Trial</a>
                        <a href="/demo-guide" class="btn btn-outline-light btn-lg px-4">Demo Guide</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-gas-pump" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Complete Fuel Management Solution</h2>
                <p class="lead">Everything you need to run a modern, efficient fuel station</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h4 class="card-title">Station Management</h4>
                            <p class="card-text">Centralized control of multiple fuel stations with real-time monitoring, inventory tracking, and performance analytics.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="card-title">Customer Management</h4>
                            <p class="card-text">Streamlined customer onboarding with document verification, vehicle management, and credit limit controls.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h4 class="card-title">Order Processing</h4>
                            <p class="card-text">Efficient order management from placement to completion with real-time tracking and automated workflows.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h4 class="card-title">Payment Management</h4>
                            <p class="card-text">Secure payment processing with proof verification, automatic balance updates, and comprehensive financial tracking.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h4 class="card-title">Pricing Management</h4>
                            <p class="card-text">Dynamic pricing control with station-specific rates, bulk updates, and historical price tracking.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="card-title">Reporting & Analytics</h4>
                            <p class="card-text">Comprehensive reporting with real-time dashboards, performance metrics, and business intelligence insights.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Fuel Station?</h2>
            <p class="lead mb-5">Join PetroAfrica and experience the future of fuel management</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/register" class="btn btn-light btn-lg px-4">Start Free Trial</a>
                <a href="/demo-guide" class="btn btn-outline-light btn-lg px-4">View Demo Guide</a>
                <a href="/login" class="btn btn-outline-light btn-lg px-4">Login to Demo</a>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
