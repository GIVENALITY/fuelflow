<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Station;
use App\Models\Client;
use App\Models\FuelRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        // No middleware in constructor - we'll handle authorization in each method
    }

    private function checkSuperAdminAccess()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
        
        return null; // No redirect means authorized
    }

    public function dashboard()
    {
        // Redirect to main dashboard which already handles super admin users correctly
        return redirect()->route('dashboard');
    }

    public function manageStations()
    {
        $redirect = $this->checkSuperAdminAccess();
        if ($redirect) return $redirect;

        $stations = Station::with(['manager', 'location'])->get();
        return view('stations.index', compact('stations'));
    }

    public function createStation()
    {
        $redirect = $this->checkSuperAdminAccess();
        if ($redirect) return $redirect;

        return view('stations.create');
    }

    public function storeStation(Request $request)
    {
        $redirect = $this->checkSuperAdminAccess();
        if ($redirect) return $redirect;

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:stations,code',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'capacity_diesel' => 'required|numeric|min:0',
            'capacity_petrol' => 'required|numeric|min:0',
            'current_diesel_level' => 'required|numeric|min:0',
            'current_petrol_level' => 'required|numeric|min:0',
        ]);

        Station::create($request->all());

        return redirect()->route('super-admin.stations.index')
            ->with('success', 'Station created successfully.');
    }

    public function manageUsers()
    {
        $redirect = $this->checkSuperAdminAccess();
        if ($redirect) return $redirect;

        $users = User::with(['station', 'client'])->get();
        return view('super-admin.users', compact('users'));
    }

    public function createUser()
    {
        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        return view('users.create', compact('stations'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin,station_manager,station_attendant,treasury,client',
            'station_id' => 'nullable|exists:stations,id',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function toggleUserStatus(User $user)
    {
        $newStatus = $user->status === User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
        $user->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'User status updated successfully.');
    }

    public function toggleStationStatus(Station $station)
    {
        $newStatus = $station->status === Station::STATUS_ACTIVE ? Station::STATUS_INACTIVE : Station::STATUS_ACTIVE;
        $station->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Station status updated successfully.');
    }

    public function reports()
    {
        $redirect = $this->checkSuperAdminAccess();
        if ($redirect) return $redirect;

        // Generate comprehensive reports data
        $reports = $this->generateComprehensiveReports();
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        return view('reports.index', compact('reports', 'dateFrom', 'dateTo'));
    }

    private function generateComprehensiveReports()
    {
        $dateFrom = request('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = request('date_to', now()->format('Y-m-d'));

        // Get total requests
        $totalRequests = FuelRequest::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        
        // Get credit sales (total amount requested)
        $totalCreditSales = FuelRequest::whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount');
        
        // Get actual revenue (completed payments)
        $totalActualRevenue = Payment::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('amount');

        // Status breakdown
        $statusBreakdown = FuelRequest::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Daily trends (last 7 days)
        $dailyTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $requests = FuelRequest::whereDate('created_at', $date)->count();
            $revenue = Payment::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('amount');
            
            $dailyTrends[] = [
                'date' => $date,
                'requests' => $requests,
                'revenue' => $revenue
            ];
        }

        return [
            'total_requests' => $totalRequests,
            'total_credit_sales' => $totalCreditSales,
            'total_actual_revenue' => $totalActualRevenue,
            'status_breakdown' => $statusBreakdown,
            'daily_trends' => $dailyTrends
        ];
    }

    private function generateFuelSalesReport()
    {
        return FuelRequest::select(
            'fuel_type',
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(quantity_dispensed) as total_liters'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
        ->whereNotNull('quantity_dispensed')
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('fuel_type')
        ->get();
    }

    private function generateClientActivityReport()
    {
        return Client::select(
            'company_name',
            DB::raw('COUNT(fuel_requests.id) as total_orders'),
            DB::raw('SUM(fuel_requests.quantity_dispensed) as total_liters'),
            DB::raw('SUM(fuel_requests.total_amount) as total_spent')
        )
        ->leftJoin('fuel_requests', 'clients.id', '=', 'fuel_requests.client_id')
        ->where('fuel_requests.created_at', '>=', now()->subDays(30))
        ->groupBy('clients.id', 'clients.company_name')
        ->orderBy('total_spent', 'desc')
        ->get();
    }

    private function generateStationPerformanceReport()
    {
        return Station::select(
            'stations.name',
            DB::raw('COUNT(fuel_requests.id) as total_orders'),
            DB::raw('SUM(fuel_requests.quantity_dispensed) as total_liters'),
            DB::raw('SUM(fuel_requests.total_amount) as total_revenue')
        )
        ->leftJoin('fuel_requests', 'stations.id', '=', 'fuel_requests.station_id')
        ->where('fuel_requests.created_at', '>=', now()->subDays(30))
        ->groupBy('stations.id', 'stations.name')
        ->orderBy('total_revenue', 'desc')
        ->get();
    }
}
