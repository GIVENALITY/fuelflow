<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $stations = Station::with(['manager', 'location'])->get();
        } elseif ($user->isStationManager()) {
            $stations = Station::where('id', $user->station_id)->with(['manager', 'location'])->get();
        } else {
            $stations = collect();
        }

        return view('stations.index', compact('stations'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $managers = User::where('role', User::ROLE_STATION_MANAGER)->get();
        $locations = Location::active()->get();
        return view('stations.create', compact('managers', 'locations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:stations,code',
            'location_id' => 'required|exists:locations,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive'
        ]);

        Station::create($request->all());

        return redirect()->route('stations.index')->with('success', 'Station created successfully.');
    }

    public function show(Station $station)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $user->station_id !== $station->id) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $station->load(['manager', 'location', 'fuelRequests', 'receipts']);
        
        return view('stations.show', compact('station'));
    }

    public function edit(Station $station)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $managers = User::where('role', User::ROLE_STATION_MANAGER)->get();
        $locations = Location::active()->get();
        return view('stations.edit', compact('station', 'managers', 'locations'));
    }

    public function update(Request $request, Station $station)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:stations,code,' . $station->id,
            'location_id' => 'required|exists:locations,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive'
        ]);

        $station->update($request->all());

        return redirect()->route('stations.index')->with('success', 'Station updated successfully.');
    }

    public function destroy(Station $station)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('stations.index')->with('error', 'Unauthorized access.');
        }

        $station->delete();

        return redirect()->route('stations.index')->with('success', 'Station deleted successfully.');
    }
}
