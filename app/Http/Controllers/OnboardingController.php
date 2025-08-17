<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user-specific onboarding data
        $onboardingData = $this->getOnboardingData($user);
        
        return view('onboarding.index', compact('onboardingData', 'user'));
    }

    public function markComplete(Request $request)
    {
        $user = Auth::user();
        
        // Mark onboarding as complete for the user
        $user->update([
            'onboarding_completed' => true,
            'onboarding_completed_at' => now()
        ]);

        return redirect()->route('dashboard')->with('success', 'Welcome to FuelFlow! You\'re all set to start managing your fuel operations.');
    }

    private function getOnboardingData($user)
    {
        $data = [
            'steps' => [
                [
                    'title' => 'Welcome to FuelFlow',
                    'description' => 'Your comprehensive fuel management solution',
                    'icon' => 'dashboard',
                    'color' => 'primary',
                    'content' => [
                        'FuelFlow is designed to streamline your fuel operations',
                        'Manage clients, vehicles, routes, and fuel requests efficiently',
                        'Track payments, receipts, and generate detailed reports'
                    ]
                ],
                [
                    'title' => 'User Roles & Permissions',
                    'description' => 'Understanding your access level',
                    'icon' => 'security',
                    'color' => 'info',
                    'content' => $this->getRoleSpecificContent($user)
                ],
                [
                    'title' => 'Key Features Overview',
                    'description' => 'Essential tools at your fingertips',
                    'icon' => 'apps',
                    'color' => 'success',
                    'content' => [
                        'Dashboard: Real-time overview of your operations',
                        'Fuel Requests: Create and manage fuel delivery requests',
                        'Client Management: Handle client accounts and vehicles',
                        'Route Planning: Optimize delivery routes between locations',
                        'Payment Tracking: Monitor payments and outstanding balances',
                        'Reports: Generate detailed analytics and insights'
                    ]
                ],
                [
                    'title' => 'Getting Started',
                    'description' => 'Your first steps in FuelFlow',
                    'icon' => 'rocket_launch',
                    'color' => 'warning',
                    'content' => $this->getGettingStartedContent($user)
                ],
                [
                    'title' => 'Best Practices',
                    'description' => 'Tips for optimal usage',
                    'icon' => 'lightbulb',
                    'color' => 'secondary',
                    'content' => [
                        'Keep client information up to date',
                        'Regularly update vehicle fuel levels',
                        'Monitor credit limits and payment schedules',
                        'Use route optimization for efficient deliveries',
                        'Generate regular reports for insights',
                        'Set up notifications for important events'
                    ]
                ]
            ],
            'quickActions' => $this->getQuickActions($user),
            'helpResources' => [
                [
                    'title' => 'User Manual',
                    'description' => 'Comprehensive guide to all features',
                    'icon' => 'menu_book',
                    'url' => '#'
                ],
                [
                    'title' => 'Video Tutorials',
                    'description' => 'Step-by-step video guides',
                    'icon' => 'play_circle',
                    'url' => '#'
                ],
                [
                    'title' => 'Support Center',
                    'description' => 'Get help when you need it',
                    'icon' => 'support_agent',
                    'url' => '#'
                ]
            ]
        ];

        return $data;
    }

    private function getRoleSpecificContent($user)
    {
        if ($user->isAdmin()) {
            return [
                'Full system access and management capabilities',
                'Create and manage user accounts',
                'Configure system settings and fuel prices',
                'Access all reports and analytics',
                'Manage locations, routes, and stations'
            ];
        } elseif ($user->isStationManager()) {
            return [
                'Manage your station\'s fuel operations',
                'Approve and assign fuel requests',
                'Track fuel inventory and dispensing',
                'Generate station-specific reports',
                'Manage station staff and assignments'
            ];
        } elseif ($user->isFuelPumper()) {
            return [
                'View assigned fuel requests',
                'Update fuel dispensing status',
                'Track your daily activities',
                'Generate personal activity reports',
                'Manage your work schedule'
            ];
        } elseif ($user->isTreasury()) {
            return [
                'Manage payments and receipts',
                'Track outstanding balances',
                'Generate financial reports',
                'Monitor credit limits',
                'Handle payment approvals'
            ];
        } else {
            return [
                'Create fuel requests for your vehicles',
                'Track request status and history',
                'View your account balance and credit',
                'Manage your vehicle fleet',
                'Access your payment history'
            ];
        }
    }

    private function getGettingStartedContent($user)
    {
        if ($user->isAdmin()) {
            return [
                'Set up your first station and location',
                'Create client accounts and add vehicles',
                'Configure fuel prices and credit limits',
                'Create delivery routes between locations',
                'Invite team members and assign roles'
            ];
        } elseif ($user->isStationManager()) {
            return [
                'Review your station settings',
                'Check current fuel inventory',
                'Review pending fuel requests',
                'Assign staff to requests',
                'Set up your daily workflow'
            ];
        } elseif ($user->isFuelPumper()) {
            return [
                'Check your assigned requests',
                'Review vehicle and client details',
                'Prepare for fuel dispensing',
                'Update request statuses',
                'Track your daily progress'
            ];
        } elseif ($user->isTreasury()) {
            return [
                'Review pending receipts',
                'Check outstanding balances',
                'Set up payment reminders',
                'Configure credit alerts',
                'Prepare financial reports'
            ];
        } else {
            return [
                'Add your vehicles to the system',
                'Create your first fuel request',
                'Select your preferred station',
                'Set up payment preferences',
                'Track your request status'
            ];
        }
    }

    private function getQuickActions($user)
    {
        $actions = [];

        if ($user->isAdmin()) {
            $actions = [
                ['title' => 'Add Client', 'route' => 'clients.create', 'icon' => 'person_add'],
                ['title' => 'Create Route', 'route' => 'routes.create', 'icon' => 'route'],
                ['title' => 'Add Location', 'route' => 'locations.create', 'icon' => 'location_on'],
                ['title' => 'View Reports', 'route' => 'reports.index', 'icon' => 'analytics']
            ];
        } elseif ($user->isStationManager()) {
            $actions = [
                ['title' => 'View Requests', 'route' => 'fuel-requests.index', 'icon' => 'assignment'],
                ['title' => 'Check Inventory', 'route' => 'station.inventory', 'icon' => 'inventory'],
                ['title' => 'Manage Staff', 'route' => 'users.index', 'icon' => 'people'],
                ['title' => 'Station Reports', 'route' => 'reports.station', 'icon' => 'analytics']
            ];
        } elseif ($user->isFuelPumper()) {
            $actions = [
                ['title' => 'My Assignments', 'route' => 'fuel-requests.my-assignments', 'icon' => 'assignment_ind'],
                ['title' => 'Update Status', 'route' => 'fuel-requests.index', 'icon' => 'update'],
                ['title' => 'My Activity', 'route' => 'reports.my-activity', 'icon' => 'timeline']
            ];
        } elseif ($user->isTreasury()) {
            $actions = [
                ['title' => 'Pending Receipts', 'route' => 'receipts.pending', 'icon' => 'pending_actions'],
                ['title' => 'Overdue Accounts', 'route' => 'clients.overdue', 'icon' => 'warning'],
                ['title' => 'Financial Reports', 'route' => 'reports.financial', 'icon' => 'account_balance']
            ];
        } else {
            $actions = [
                ['title' => 'New Request', 'route' => 'fuel-requests.create', 'icon' => 'add'],
                ['title' => 'My Vehicles', 'route' => 'vehicles.index', 'icon' => 'directions_car'],
                ['title' => 'Payment History', 'route' => 'payments.index', 'icon' => 'receipt']
            ];
        }

        return $actions;
    }
}
