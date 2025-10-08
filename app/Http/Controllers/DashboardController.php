<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use App\Models\Client;
use App\Models\FuelRequest;
use App\Models\Receipt;
use App\Models\Payment;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If no user is authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->isSuperAdmin()) {
            return $this->superAdminDashboard();
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isStationManager()) {
            return $this->stationManagerDashboard();
        } elseif ($user->isStationAttendant()) {
            return $this->stationAttendantDashboard();
        } elseif ($user->isTreasury()) {
            return $this->treasuryDashboard();
        } else {
            return $this->clientDashboard();
        }
    }

    private function superAdminDashboard()
    {
        try {
            // Check if Business model exists and has data
            $businessCount = 0;
            $pendingBusinesses = 0;
            $activeBusinesses = 0;
            $avgStationsPerBusiness = 0;
            
            try {
                $businessCount = \App\Models\Business::count();
                $pendingBusinesses = \App\Models\Business::where('status', \App\Models\Business::STATUS_PENDING)->count();
                $activeBusinesses = \App\Models\Business::where('status', \App\Models\Business::STATUS_APPROVED)->count();
                
                if ($activeBusinesses > 0) {
                    $avgStationsPerBusiness = \App\Models\Business::where('status', \App\Models\Business::STATUS_APPROVED)
                        ->withCount('stations')
                        ->get()
                        ->avg('stations_count') ?? 0;
                }
            } catch (\Exception $e) {
                // Business table might not exist yet
                \Log::info('Business table not ready: ' . $e->getMessage());
            }

            $stats = [
                'total_businesses' => $businessCount,
                'pending_businesses' => $pendingBusinesses,
                'active_businesses' => $activeBusinesses,
                'total_stations' => Station::count(),
                'total_clients' => Client::count(),
                'total_revenue' => Payment::where('status', 'completed')->sum('amount') ?? 0,
                'monthly_sales' => FuelRequest::where('status', FuelRequest::STATUS_DISPENSED)
                    ->whereMonth('dispensed_at', now()->month)
                    ->whereYear('dispensed_at', now()->year)
                    ->sum('quantity_dispensed') ?? 0,
                'avg_stations_per_business' => $avgStationsPerBusiness,
            ];

            return view('dashboard', compact('stats'));
        } catch (\Exception $e) {
            \Log::error('SuperAdmin Dashboard Error: ' . $e->getMessage());
            return view('dashboard', ['stats' => [
                'total_businesses' => 0,
                'pending_businesses' => 0,
                'active_businesses' => 0,
                'total_stations' => 0,
                'total_clients' => 0,
                'total_revenue' => 0,
                'monthly_sales' => 0,
                'avg_stations_per_business' => 0,
            ]]);
        }
    }

    private function adminDashboard()
    {
        $user = Auth::user();
        $businessId = $user->business_id ?? null;
        
        // Simple queries without complex relationships - FOR DEMO
        $totalRevenue = 0;
        $activeClients = 0;
        $totalStations = 0;
        $pendingApprovals = 0;
        $recentRequests = [];
        
        if ($businessId) {
            try {
                $activeClients = Client::where('business_id', $businessId)->count();
            } catch (\Exception $e) {
                $activeClients = 0;
            }
            
            try {
                $totalStations = Station::where('business_id', $businessId)->count();
            } catch (\Exception $e) {
                $totalStations = 0;
            }
            
            try {
                $pendingApprovals = FuelRequest::where('status', 'pending')->count();
            } catch (\Exception $e) {
                $pendingApprovals = 0;
            }
            
            try {
                $recentRequests = FuelRequest::latest()->take(5)->get();
            } catch (\Exception $e) {
                $recentRequests = [];
            }
        }

        // Business info
        $business = (object)[
            'name' => 'Petro Africa',
            'id' => $businessId
        ];

        return view('dashboard.admin', compact(
            'totalRevenue',
            'activeClients',
            'totalStations', 
            'pendingApprovals',
            'recentRequests',
            'business'
        ));
    }

    private function stationManagerDashboard()
    {
        $user = Auth::user();
        $station = $user->station;
        
        // Check if station exists and is assigned
        if (!$station) {
            return view('dashboard', [
                'error' => 'No station assigned to you. Please contact an administrator.',
                'todayRequests' => 0,
                'fuelDispensedToday' => 0,
                'availableStaff' => 0,
                'pendingRequests' => 0,
                'recentRequests' => collect()
            ]);
        }
        
        $todayRequests = FuelRequest::where('station_id', $station->id)
            ->whereDate('request_date', today())
            ->count();
        $fuelDispensedToday = FuelRequest::where('station_id', $station->id)
            ->where('status', FuelRequest::STATUS_DISPENSED)
            ->whereDate('dispensed_at', today())
            ->sum('quantity_dispensed');
        $availableStaff = User::where('station_id', $station->id)
            ->where('role', User::ROLE_STATION_ATTENDANT)
            ->where('status', User::STATUS_ACTIVE)
            ->count();
        $pendingRequests = FuelRequest::where('station_id', $station->id)
            ->where('status', FuelRequest::STATUS_PENDING)
            ->count();

        $recentRequests = FuelRequest::with(['client', 'vehicle'])
            ->where('station_id', $station->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todayRequests',
            'fuelDispensedToday',
            'availableStaff',
            'pendingRequests',
            'recentRequests'
        ));
    }

    private function stationAttendantDashboard()
    {
        $user = Auth::user();
        
        $assignedRequests = FuelRequest::where('assigned_pumper_id', $user->id)
            ->where('status', FuelRequest::STATUS_APPROVED)
            ->count();
        $completedToday = FuelRequest::where('dispensed_by', $user->id)
            ->where('status', FuelRequest::STATUS_DISPENSED)
            ->whereDate('dispensed_at', today())
            ->count();
        $fuelDispensed = FuelRequest::where('dispensed_by', $user->id)
            ->where('status', FuelRequest::STATUS_DISPENSED)
            ->whereDate('dispensed_at', today())
            ->sum('quantity_dispensed');
        $pendingTasks = FuelRequest::where('assigned_pumper_id', $user->id)
            ->whereIn('status', [FuelRequest::STATUS_APPROVED, FuelRequest::STATUS_IN_PROGRESS])
            ->count();

        $assignedRequestsList = FuelRequest::with(['client', 'vehicle'])
            ->where('assigned_pumper_id', $user->id)
            ->whereIn('status', [FuelRequest::STATUS_APPROVED, FuelRequest::STATUS_IN_PROGRESS])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'assignedRequests',
            'completedToday',
            'fuelDispensed',
            'pendingTasks',
            'assignedRequestsList'
        ));
    }

    private function treasuryDashboard()
    {
        $outstandingBalances = Client::sum('current_balance');
        $pendingReceipts = Receipt::where('status', Receipt::STATUS_PENDING)->count();
        $overdueAccounts = Client::where('current_balance', '>', 0)
            ->where('status', Client::STATUS_ACTIVE)
            ->count();
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        $pendingReceiptsList = Receipt::with(['client', 'fuelRequest'])
            ->where('status', Receipt::STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'outstandingBalances',
            'pendingReceipts',
            'overdueAccounts',
            'monthlyRevenue',
            'pendingReceiptsList'
        ));
    }

    private function clientDashboard()
    {
        // Redirect clients to the dedicated client portal dashboard
        return redirect()->route('client-portal.dashboard');
    }
}
