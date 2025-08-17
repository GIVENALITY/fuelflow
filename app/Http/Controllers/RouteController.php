<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Location;
use App\Models\RouteStop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $routes = Route::with(['startLocation', 'endLocation', 'routeStops.location'])->get();
        } else {
            $routes = Route::active()->with(['startLocation', 'endLocation', 'routeStops.location'])->get();
        }

        return view('routes.index', compact('routes'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('routes.index')->with('error', 'Unauthorized access.');
        }

        $locations = Location::active()->get();
        return view('routes.create', compact('locations'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('routes.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_location_id' => 'required|exists:locations,id',
            'end_location_id' => 'required|exists:locations,id|different:start_location_id',
            'total_distance' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,active,inactive,maintenance',
            'is_active' => 'boolean',
            'stops' => 'array',
            'stops.*.location_id' => 'required|exists:locations,id',
            'stops.*.order' => 'required|integer|min:1',
            'stops.*.estimated_time' => 'nullable|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $route = Route::create([
                'name' => $request->name,
                'description' => $request->description,
                'start_location_id' => $request->start_location_id,
                'end_location_id' => $request->end_location_id,
                'total_distance' => $request->total_distance,
                'estimated_duration' => $request->estimated_duration,
                'status' => $request->status,
                'is_active' => $request->boolean('is_active'),
                'created_by' => Auth::id()
            ]);

            // Add route stops if provided
            if ($request->has('stops')) {
                foreach ($request->stops as $stop) {
                    $route->routeStops()->create([
                        'location_id' => $stop['location_id'],
                        'order' => $stop['order'],
                        'estimated_time' => $stop['estimated_time'] ?? null
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('routes.index')->with('success', 'Route created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create route: ' . $e->getMessage());
        }
    }

    public function show(Route $route)
    {
        $route->load(['startLocation', 'endLocation', 'routeStops.location', 'createdBy']);
        return view('routes.show', compact('route'));
    }

    public function edit(Route $route)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('routes.index')->with('error', 'Unauthorized access.');
        }

        $locations = Location::active()->get();
        $route->load('routeStops.location');
        return view('routes.edit', compact('route', 'locations'));
    }

    public function update(Request $request, Route $route)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('routes.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_location_id' => 'required|exists:locations,id',
            'end_location_id' => 'required|exists:locations,id|different:start_location_id',
            'total_distance' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,active,inactive,maintenance',
            'is_active' => 'boolean',
            'stops' => 'array',
            'stops.*.location_id' => 'required|exists:locations,id',
            'stops.*.order' => 'required|integer|min:1',
            'stops.*.estimated_time' => 'nullable|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $route->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_location_id' => $request->start_location_id,
                'end_location_id' => $request->end_location_id,
                'total_distance' => $request->total_distance,
                'estimated_duration' => $request->estimated_duration,
                'status' => $request->status,
                'is_active' => $request->boolean('is_active')
            ]);

            // Update route stops
            $route->routeStops()->delete();
            if ($request->has('stops')) {
                foreach ($request->stops as $stop) {
                    $route->routeStops()->create([
                        'location_id' => $stop['location_id'],
                        'order' => $stop['order'],
                        'estimated_time' => $stop['estimated_time'] ?? null
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('routes.index')->with('success', 'Route updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update route: ' . $e->getMessage());
        }
    }

    public function destroy(Route $route)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('routes.index')->with('error', 'Unauthorized access.');
        }

        $route->delete();

        return redirect()->route('routes.index')->with('success', 'Route deleted successfully.');
    }

    public function reorderStops(Request $request, Route $route)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'stop_ids' => 'required|array',
            'stop_ids.*' => 'exists:route_stops,id'
        ]);

        try {
            $route->reorderStops($request->stop_ids);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to reorder stops.'], 500);
        }
    }

    public function addStop(Request $request, Route $route)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'order' => 'required|integer|min:1',
            'estimated_time' => 'nullable|integer|min:0'
        ]);

        try {
            $stop = $route->addLocation(
                Location::find($request->location_id),
                $request->order,
                $request->estimated_time
            );

            return response()->json([
                'success' => true,
                'stop' => $stop->load('location')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add stop.'], 500);
        }
    }

    public function removeStop(Request $request, Route $route, RouteStop $stop)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        if ($stop->route_id !== $route->id) {
            return response()->json(['error' => 'Invalid stop.'], 400);
        }

        try {
            $stop->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove stop.'], 500);
        }
    }
}
