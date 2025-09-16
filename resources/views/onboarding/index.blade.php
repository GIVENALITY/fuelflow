@extends('layouts.app')

@section('title', 'Welcome to FuelFlow - Onboarding Guide')

@push('styles')
    <style>
        .onboarding-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .onboarding-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e9ecef;
            margin: 0 8px;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background: #667eea;
            transform: scale(1.2);
        }

        .step-dot.completed {
            background: #28a745;
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .quick-action-btn {
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            color: inherit;
        }

        .help-resource-card {
            border: 2px solid #f8f9fa;
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .help-resource-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.3s ease;
        }

        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .role-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            margin-top: 1rem;
        }

        /* Workflow Timeline Styles */
        .workflow-timeline {
            position: relative;
            padding-left: 2rem;
        }

        .workflow-timeline::before {
            content: '';
            position: absolute;
            left: 25px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .workflow-step {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 3rem;
        }

        .workflow-step:last-child {
            margin-bottom: 0;
        }

        .workflow-step-number {
            position: absolute;
            left: -25px;
            top: 0;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            z-index: 2;
        }

        .workflow-step-content {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .workflow-step-content:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .workflow-step-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .workflow-step-description {
            color: #6c757d;
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .workflow-timeline {
                padding-left: 1rem;
            }

            .workflow-step {
                padding-left: 2rem;
            }

            .workflow-step-number {
                left: -15px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        /* Journey Flow Styles */
        .journey-flow {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .journey-step {
            display: flex;
            align-items: center;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .journey-step:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .journey-step-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .journey-step-icon i {
            color: white;
            font-size: 1.2rem;
        }

        .journey-step-content {
            flex-grow: 1;
        }

        .journey-step-text {
            color: #495057;
            font-weight: 500;
            line-height: 1.5;
        }

        /* Responsive adjustments for journey flow */
        @media (max-width: 768px) {
            .journey-step {
                padding: 0.75rem 1rem;
            }

            .journey-step-icon {
                width: 35px;
                height: 35px;
                margin-right: 0.75rem;
            }

            .journey-step-icon i {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="onboarding-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="onboarding-card">
                        <!-- Welcome Header -->
                        <div class="welcome-header">
                            <div class="mb-4">
                                <i class="material-symbols-rounded" style="font-size: 4rem; opacity: 0.9;">rocket_launch</i>
                            </div>
                            <h1 class="display-6 fw-bold mb-3">Welcome to FuelFlow!</h1>
                            <p class="lead mb-3">Your comprehensive fuel management solution</p>
                            <div class="role-badge">
                                <i class="material-symbols-rounded me-2">person</i>
                                {{ ucfirst($user->role) }} - {{ $user->name }}
                            </div>
                        </div>

                        <!-- Progress Indicator -->
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Onboarding Progress</h6>
                                <span class="text-muted" id="progressText">1 of {{ count($onboardingData['steps']) }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill" style="width: 20%"></div>
                            </div>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            @foreach($onboardingData['steps'] as $index => $step)
                                <div class="step-dot {{ $index === 0 ? 'active' : '' }}" data-step="{{ $index + 1 }}"></div>
                            @endforeach
                        </div>

                        <!-- Content Area -->
                        <div class="p-4">
                            <div id="stepContent">
                                @foreach($onboardingData['steps'] as $index => $step)
                                    <div class="step-content {{ $index === 0 ? 'active' : 'd-none' }}"
                                        data-step="{{ $index + 1 }}">
                                        <div class="text-center mb-4">
                                            <div class="icon-shape bg-gradient-{{ $step['color'] }} shadow-{{ $step['color'] }} rounded-circle mx-auto mb-3"
                                                style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                                <i class="material-symbols-rounded text-white"
                                                    style="font-size: 2rem;">{{ $step['icon'] }}</i>
                                            </div>
                                            <h3 class="fw-bold mb-2">{{ $step['title'] }}</h3>
                                            <p class="text-muted">{{ $step['description'] }}</p>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-10 mx-auto">
                                                @if($step['title'] === 'Your Daily Workflow')
                                                    <!-- Workflow Timeline -->
                                                    <div class="workflow-timeline">
                                                        @foreach($step['content'] as $index => $item)
                                                            <div class="workflow-step">
                                                                <div class="workflow-step-number">{{ $index + 1 }}</div>
                                                                <div class="workflow-step-content">
                                                                    <h6 class="workflow-step-title">{{ explode(':', $item)[0] }}</h6>
                                                                    <p class="workflow-step-description">
                                                                        {{ explode(':', $item)[1] ?? $item }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($step['title'] === 'Complete Journey Overview')
                                                    <!-- Journey Flow -->
                                                    <div class="journey-flow">
                                                        @foreach($step['content'] as $index => $item)
                                                            <div class="journey-step">
                                                                <div class="journey-step-icon">
                                                                    <i class="material-symbols-rounded">arrow_forward</i>
                                                                </div>
                                                                <div class="journey-step-content">
                                                                    <span class="journey-step-text">{{ $item }}</span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <!-- Regular Content -->
                                                    <div class="feature-card card">
                                                        <div class="card-body">
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach($step['content'] as $item)
                                                                    <li class="d-flex align-items-start mb-3">
                                                                        <i
                                                                            class="material-symbols-rounded text-{{ $step['color'] }} me-3 mt-1">check_circle</i>
                                                                        <span>{{ $item }}</span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Navigation Buttons -->
                                        <div class="text-center mt-4">
                                            @if($index > 0)
                                                <button class="btn btn-outline-secondary me-2" onclick="previousStep()">
                                                    <i class="material-symbols-rounded me-2">arrow_back</i>Previous
                                                </button>
                                            @endif

                                            @if($index < count($onboardingData['steps']) - 1)
                                                <button class="btn btn-primary" onclick="nextStep()">
                                                    Next<i class="material-symbols-rounded ms-2">arrow_forward</i>
                                                </button>
                                            @else
                                                <button class="btn btn-success" onclick="completeOnboarding()">
                                                    <i class="material-symbols-rounded me-2">check</i>Complete Onboarding
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Help Resources -->
                    <div class="row mt-4">
                        <!-- Quick Actions -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="material-symbols-rounded text-primary me-2">flash_on</i>Quick Actions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($onboardingData['quickActions'] as $action)
                                            <div class="col-6 mb-3">
                                                <a href="{{ route($action['route'], $action['params'] ?? []) }}"
                                                    class="quick-action-btn card">
                                                    <div class="card-body text-center">
                                                        <i class="material-symbols-rounded text-primary mb-2"
                                                            style="font-size: 2rem;">{{ $action['icon'] }}</i>
                                                        <h6 class="mb-0">{{ $action['title'] }}</h6>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help Resources -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="material-symbols-rounded text-info me-2">help</i>Help Resources
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($onboardingData['helpResources'] as $resource)
                                        <div class="help-resource-card p-3 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="material-symbols-rounded text-info me-3"
                                                    style="font-size: 1.5rem;">{{ $resource['icon'] }}</i>
                                                <div>
                                                    <h6 class="mb-1">{{ $resource['title'] }}</h6>
                                                    <p class="text-muted mb-0 small">{{ $resource['description'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = {{ count($onboardingData['steps']) }};

        function updateProgress() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progressFill').style.width = progress + '%';
            document.getElementById('progressText').textContent = currentStep + ' of ' + totalSteps;
        }

        function showStep(stepNumber) {
            // Hide all step content
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.add('d-none');
            });

            // Show current step content
            document.querySelector(`[data-step="${stepNumber}"]`).classList.remove('d-none');

            // Update step indicators
            document.querySelectorAll('.step-dot').forEach((dot, index) => {
                dot.classList.remove('active', 'completed');
                if (index + 1 < stepNumber) {
                    dot.classList.add('completed');
                } else if (index + 1 === stepNumber) {
                    dot.classList.add('active');
                }
            });

            updateProgress();
        }

        function nextStep() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function completeOnboarding() {
            if (confirm('Are you ready to complete the onboarding and start using FuelFlow?')) {
                window.location.href = '{{ route("onboarding.complete") }}';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            showStep(1);
        });
    </script>
@endsection