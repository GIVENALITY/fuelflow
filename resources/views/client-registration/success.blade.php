<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success - FuelFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h2 class="text-success mb-3">Registration Submitted Successfully!</h2>
                        
                        <p class="lead mb-4">
                            Thank you for registering with FuelFlow. Your application has been submitted and is now under review.
                        </p>
                        
                        <div class="alert alert-info text-start mb-4">
                            <h5><i class="fas fa-info-circle me-2"></i>What happens next?</h5>
                            <ul class="mb-0">
                                <li>Our team will review your application and documents</li>
                                <li>We will verify your business credentials</li>
                                <li>You will receive an email notification once approved</li>
                                <li>Upon approval, you can start placing fuel orders</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning text-start mb-4">
                            <h5><i class="fas fa-clock me-2"></i>Processing Time</h5>
                            <p class="mb-0">Please allow 2-3 business days for your application to be processed. We will contact you if we need any additional information.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('client-registration.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                                    <i class="fas fa-plus me-2"></i>Register Another Company
                                </a>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <p class="text-muted">
                            <i class="fas fa-envelope me-2"></i>
                            Need help? Contact us at <a href="mailto:support@fuelflow.com">support@fuelflow.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
