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
                    'title' => 'Your Daily Workflow',
                    'description' => 'How to complete your tasks efficiently',
                    'icon' => 'timeline',
                    'color' => 'warning',
                    'content' => $this->getWorkflowContent($user)
                ],
                [
                    'title' => 'Getting Started',
                    'description' => 'Your first steps in FuelFlow',
                    'icon' => 'rocket_launch',
                    'color' => 'info',
                    'content' => $this->getGettingStartedContent($user)
                ],
                [
                    'title' => 'Complete Journey Overview',
                    'description' => 'How a fuel request flows through the system',
                    'icon' => 'account_tree',
                    'color' => 'secondary',
                    'content' => $this->getJourneyContent($user)
                ],
                [
                    'title' => 'Best Practices',
                    'description' => 'Tips for optimal usage',
                    'icon' => 'lightbulb',
                    'color' => 'dark',
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

    private function getWorkflowContent($user)
    {
        if ($user->isAdmin()) {
            return [
                '1. System Setup: Configure stations, locations, and fuel prices',
                '2. User Management: Create accounts and assign roles to team members',
                '3. Client Onboarding: Add new clients and their vehicle fleets',
                '4. Route Planning: Create efficient delivery routes between locations',
                '5. Monitoring: Review system reports and oversee operations',
                '6. Maintenance: Update system settings and manage user permissions'
            ];
        } elseif ($user->isStationManager()) {
            return [
                '1. Daily Review: Check dashboard for pending requests and alerts',
                '2. Request Approval: Review and approve incoming fuel requests',
                '3. Staff Assignment: Assign fuel pumpers to approved requests',
                '4. Inventory Management: Monitor fuel levels and update stock',
                '5. Quality Control: Ensure proper fuel dispensing and safety',
                '6. Reporting: Generate daily station reports and performance metrics'
            ];
        } elseif ($user->isFuelPumper()) {
            return [
                '1. Check Assignments: Review your assigned fuel requests for the day',
                '2. Vehicle Preparation: Verify vehicle details and fuel requirements',
                '3. Fuel Dispensing: Safely dispense fuel according to specifications',
                '4. Status Updates: Mark requests as in-progress and completed',
                '5. Receipt Management: Upload fuel receipts and delivery confirmations',
                '6. End-of-Day: Complete activity reports and handover notes'
            ];
        } elseif ($user->isTreasury()) {
            return [
                '1. Payment Review: Check for new payments and pending receipts',
                '2. Credit Monitoring: Review client credit limits and outstanding balances',
                '3. Receipt Processing: Verify and process uploaded fuel receipts',
                '4. Payment Tracking: Update payment status and send confirmations',
                '5. Overdue Management: Follow up on overdue accounts and payment reminders',
                '6. Financial Reporting: Generate daily financial summaries and reports'
            ];
        } else {
            return [
                '1. Vehicle Check: Review your vehicle fleet and fuel levels',
                '2. Request Creation: Submit new fuel requests with vehicle and location details',
                '3. Request Tracking: Monitor the status of your fuel requests',
                '4. Payment Management: Review invoices and make payments',
                '5. Receipt Collection: Download and store fuel receipts',
                '6. Account Review: Check your credit balance and payment history'
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
                ['title' => 'Check Inventory', 'route' => 'stations.inventory', 'icon' => 'inventory', 'params' => ['station' => $user->station_id]],
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

    private function getJourneyContent($user)
    {
        if ($user->isAdmin()) {
            return [
                '1. Client submits fuel request through the system',
                '2. Station manager reviews and approves the request',
                '3. Fuel pumper is assigned to handle the delivery',
                '4. Fuel is dispensed and receipt is uploaded',
                '5. Treasury processes payment and updates account',
                '6. System generates reports for monitoring and analysis'
            ];
        } elseif ($user->isStationManager()) {
            return [
                '1. Receive notification of new fuel request',
                '2. Review request details and client credit status',
                '3. Approve or reject request based on criteria',
                '4. Assign available fuel pumper to approved requests',
                '5. Monitor progress and ensure timely completion',
                '6. Review completed requests and generate station reports'
            ];
        } elseif ($user->isFuelPumper()) {
            return [
                '1. Check dashboard for new assignments',
                '2. Review vehicle and fuel requirements',
                '3. Travel to client location with fuel',
                '4. Dispense fuel safely and accurately',
                '5. Upload receipt and mark request as completed',
                '6. Update activity log and prepare for next assignment'
            ];
        } elseif ($user->isTreasury()) {
            return [
                '1. Monitor incoming fuel receipts and payments',
                '2. Verify receipt details against fuel requests',
                '3. Process payments and update client accounts',
                '4. Check credit limits and flag overdue accounts',
                '5. Send payment confirmations to clients',
                '6. Generate financial reports and reconciliation'
            ];
        } else {
            return [
                '1. Log into your account and check vehicle status',
                '2. Create new fuel request with vehicle and location details',
                '3. Wait for approval and assignment to fuel pumper',
                '4. Receive fuel delivery at your specified location',
                '5. Download receipt and verify delivery details',
                '6. Review invoice and make payment through the system'
            ];
        }
    }
}
