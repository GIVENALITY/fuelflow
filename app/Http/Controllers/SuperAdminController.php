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
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isSuperAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_stations' => Station::count(),
            'total_clients' => Client::count(),
            'pending_applications' => Client::where('registration_status', Client::REGISTRATION_STATUS_PENDING)->count(),
            'active_orders' => FuelRequest::where('status', FuelRequest::STATUS_PENDING)->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        // Get fuel sales by type
        $fuelSalesByType = FuelRequest::select('fuel_type', DB::raw('SUM(quantity_dispensed) as total_liters'))
            ->whereNotNull('quantity_dispensed')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('fuel_type')
            ->get();

        // Get fuel sales by station
        $fuelSalesByStation = FuelRequest::select('stations.name', DB::raw('SUM(quantity_dispensed) as total_liters'))
            ->join('stations', 'fuel_requests.station_id', '=', 'stations.id')
            ->whereNotNull('quantity_dispensed')
            ->where('fuel_requests.created_at', '>=', now()->subDays(30))
            ->groupBy('stations.id', 'stations.name')
            ->get();

        // Get fuel sales by client
        $fuelSalesByClient = FuelRequest::select('clients.company_name', DB::raw('SUM(quantity_dispensed) as total_liters'))
            ->join('clients', 'fuel_requests.client_id', '=', 'clients.id')
            ->whereNotNull('quantity_dispensed')
            ->where('fuel_requests.created_at', '>=', now()->subDays(30))
            ->groupBy('clients.id', 'clients.company_name')
            ->get();

        // Recent activities
        $recentActivities = collect([
            // Recent client registrations
            Client::with('user')->latest()->take(5)->get()->map(function($client) {
                return [
                    'type' => 'client_registration',
                    'message' => 'New client registered: ' . $client->company_name,
                    'time' => $client->created_at,
                ];
            }),
            // Recent fuel requests
            FuelRequest::with(['client', 'vehicle'])->latest()->take(5)->get()->map(function($request) {
                return [
                    'type' => 'fuel_request',
                    'message' => 'New fuel request from ' . $request->client->company_name,
                    'time' => $request->created_at,
                ];
            }),
            // Recent payments
            Payment::with('client')->latest()->take(5)->get()->map(function($payment) {
                return [
                    'type' => 'payment',
                    'message' => 'Payment submitted by ' . $payment->client->company_name,
                    'time' => $payment->created_at,
                ];
            }),
        ])->flatten()->sortByDesc('time')->take(10);

        return view('super-admin.dashboard', compact(
            'stats', 
            'fuelSalesByType', 
            'fuelSalesByStation', 
            'fuelSalesByClient',
            'recentActivities'
        ));
    }

    public function manageStations()
    {
        $stations = Station::with(['manager', 'location'])->get();
        return view('super-admin.stations.index', compact('stations'));
    }

    public function createStation()
    {
        return view('super-admin.stations.create');
    }

    public function storeStation(Request $request)
    {
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
        $users = User::with(['station', 'client'])->get();
        return view('super-admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $stations = Station::where('status', Station::STATUS_ACTIVE)->get();
        return view('super-admin.users.create', compact('stations'));
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
        // Generate various reports
        $fuelSalesReport = $this->generateFuelSalesReport();
        $clientActivityReport = $this->generateClientActivityReport();
        $stationPerformanceReport = $this->generateStationPerformanceReport();

        return view('super-admin.reports.index', compact(
            'fuelSalesReport',
            'clientActivityReport', 
            'stationPerformanceReport'
        ));
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
