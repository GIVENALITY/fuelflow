<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelRequest;
use App\Models\Client;
use App\Models\Station;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function operational()
    {
        $totalRequests = FuelRequest::count();
        $pendingRequests = FuelRequest::where('status', FuelRequest::STATUS_PENDING)->count();
        $completedRequests = FuelRequest::where('status', FuelRequest::STATUS_COMPLETED)->count();
        
        return view('reports.operational', compact('totalRequests', 'pendingRequests', 'completedRequests'));
    }

    public function financial()
    {
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingReceipts = Receipt::pending()->count();
        $overdueAccounts = Client::where('current_balance', '>', 0)->count();
        
        return view('reports.financial', compact('totalRevenue', 'pendingReceipts', 'overdueAccounts'));
    }

    public function clientAnalytics()
    {
        $clients = Client::with('fuelRequests')->get();
        return view('reports.client-analytics', compact('clients'));
    }

    public function system()
    {
        $totalUsers = \App\Models\User::count();
        $totalStations = Station::count();
        $totalClients = Client::count();
        $totalVehicles = \App\Models\Vehicle::count();
        
        return view('reports.system', compact('totalUsers', 'totalStations', 'totalClients', 'totalVehicles'));
    }

    public function station()
    {
        $user = Auth::user();
        $station = $user->station;
        
        if (!$station) {
            return redirect()->route('reports.index')->with('error', 'No station assigned.');
        }
        
        $stationRequests = FuelRequest::where('station_id', $station->id)->count();
        $pendingRequests = FuelRequest::where('station_id', $station->id)
            ->where('status', FuelRequest::STATUS_PENDING)->count();
        
        return view('reports.station', compact('station', 'stationRequests', 'pendingRequests'));
    }

    public function myActivity()
    {
        $user = Auth::user();
        
        if ($user->isFuelPumper()) {
            $assignedRequests = FuelRequest::where('assigned_pumper_id', $user->id)->count();
            $completedRequests = FuelRequest::where('dispensed_by', $user->id)->count();
        } else {
            $assignedRequests = 0;
            $completedRequests = 0;
        }
        
        return view('reports.my-activity', compact('assignedRequests', 'completedRequests'));
    }
}
