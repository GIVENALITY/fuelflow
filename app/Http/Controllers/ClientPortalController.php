<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FuelRequest;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class ClientPortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            return redirect()->route('dashboard')->with('error', 'No client profile found. Please contact an administrator.');
        }

        // Fetch dashboard statistics
        $activeVehicles = Vehicle::where('client_id', $client->id)
            ->where('status', 'active')
            ->count();
        
        $pendingRequests = FuelRequest::where('client_id', $client->id)
            ->whereIn('status', [FuelRequest::STATUS_PENDING, FuelRequest::STATUS_PENDING_APPROVAL])
            ->count();
        
        $monthlyRequests = FuelRequest::where('client_id', $client->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get recent fuel requests (max 10)
        $recentRequests = FuelRequest::with(['vehicle', 'station'])
            ->where('client_id', $client->id)
            ->latest()
            ->take(10)
            ->get();

        return view('client-portal.dashboard', compact(
            'client',
            'activeVehicles',
            'pendingRequests',
            'monthlyRequests',
            'recentRequests'
        ));
    }
}

