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
            try {
                $user = Auth::user();
                if (!$user || !$user->isSuperAdmin()) {
                    return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
                }
                return $next($request);
            } catch (\Exception $e) {
                \Log::error('SuperAdmin middleware error: ' . $e->getMessage());
                return response()->json([
                    'error_type' => 'middleware_error',
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine()
                ], 500);
            }
        });
    }

    public function dashboard()
    {
        // Redirect to main dashboard which already handles super admin users correctly
        return redirect()->route('dashboard');
    }

    public function manageStations()
    {
        $stations = Station::with(['manager', 'location'])->get();
        return view('stations.index', compact('stations'));
    }

    public function createStation()
    {
        return view('stations.create');
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
        try {
            $users = User::with(['station', 'client'])->get();
            
            // Try to render the view and catch any errors
            try {
                return view('users.index', compact('users'));
            } catch (\Exception $viewError) {
                return response()->json([
                    'error_type' => 'view_error',
                    'error_message' => $viewError->getMessage(),
                    'error_file' => $viewError->getFile(),
                    'error_line' => $viewError->getLine(),
                    'error_trace' => $viewError->getTraceAsString(),
                    'data_available' => [
                        'users_count' => $users->count(),
                        'sample_user' => $users->first() ? [
                            'id' => $users->first()->id,
                            'name' => $users->first()->name,
                            'role' => $users->first()->role
                        ] : null
                    ]
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error_type' => 'controller_error',
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString()
            ], 500);
        }
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
        // Generate various reports
        $fuelSalesReport = $this->generateFuelSalesReport();
        $clientActivityReport = $this->generateClientActivityReport();
        $stationPerformanceReport = $this->generateStationPerformanceReport();

        return view('reports.index', compact(
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
