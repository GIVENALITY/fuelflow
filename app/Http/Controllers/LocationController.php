<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $locations = Location::with('routes')->get();
        } else {
            $locations = Location::active()->with('routes')->get();
        }

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('locations.index')->with('error', 'Unauthorized access.');
        }

        return view('locations.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('locations.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:depot,station,client_location,other',
            'status' => 'required|in:active,inactive',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);

        Location::create($request->all());

        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function show(Location $location)
    {
        $location->load(['routes', 'fuelRequests', 'deliveries']);
        return view('locations.show', compact('location'));
    }

    public function edit(Location $location)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('locations.index')->with('error', 'Unauthorized access.');
        }

        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('locations.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:depot,station,client_location,other',
            'status' => 'required|in:active,inactive',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);

        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('locations.index')->with('error', 'Unauthorized access.');
        }

        // Check if location is used in any routes
        if ($location->routes()->count() > 0) {
            return redirect()->route('locations.index')->with('error', 'Cannot delete location that is used in routes.');
        }

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }

    public function getByType(Request $request)
    {
        $type = $request->get('type');
        $locations = Location::active()->byType($type)->get(['id', 'name', 'city', 'state']);
        
        return response()->json($locations);
    }
}
