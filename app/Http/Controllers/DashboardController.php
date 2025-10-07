<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use App\Models\Client;
use App\Models\FuelRequest;
use App\Models\Receipt;
use App\Models\Payment;
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
        $stats = [
            'total_users' => User::count(),
            'total_stations' => Station::count(),
            'total_clients' => Client::count(),
            'pending_applications' => Client::where('registration_status', Client::REGISTRATION_STATUS_PENDING)->count(),
            'active_orders' => FuelRequest::where('status', FuelRequest::STATUS_PENDING)->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        return view('dashboard', compact('stats'));
    }

    private function adminDashboard()
    {
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $activeClients = Client::where('status', Client::STATUS_ACTIVE)->count();
        $totalStations = Station::where('status', Station::STATUS_ACTIVE)->count();
        $pendingApprovals = FuelRequest::where('status', FuelRequest::STATUS_PENDING)->count();
        
        $recentRequests = FuelRequest::with(['client', 'vehicle', 'station'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRevenue',
            'activeClients',
            'totalStations', 
            'pendingApprovals',
            'recentRequests'
        ));
    }

    private function stationManagerDashboard()
    {
        $station = Auth::user()->station;
        
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
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            return view('dashboard');
        }

        $totalRequests = FuelRequest::where('client_id', $client->id)->count();
        $pendingRequests = FuelRequest::where('client_id', $client->id)
            ->where('status', FuelRequest::STATUS_PENDING)
            ->count();
        $completedRequests = FuelRequest::where('client_id', $client->id)
            ->where('status', FuelRequest::STATUS_COMPLETED)
            ->count();
        $availableCredit = $client->available_credit;

        $recentRequests = FuelRequest::with(['vehicle', 'station'])
            ->where('client_id', $client->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'availableCredit',
            'recentRequests'
        ));
    }
}
