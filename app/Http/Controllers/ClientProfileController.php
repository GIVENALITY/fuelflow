<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isClient()) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function profile()
    {
        $client = Auth::user()->client;
        return view('client.profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = Auth::user()->client;

        $request->validate([
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'tax_id' => 'required|string|max:100',
            'tin_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'brela_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'business_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'director_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $updateData = [
                'contact_person' => $request->contact_person,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'tax_id' => $request->tax_id,
            ];

            // Handle document uploads
            if ($request->hasFile('tin_document')) {
                $updateData['tin_document_path'] = $this->uploadDocument($request->file('tin_document'), 'tin_document');
            }
            if ($request->hasFile('brela_certificate')) {
                $updateData['brela_certificate_path'] = $this->uploadDocument($request->file('brela_certificate'), 'brela_certificate');
            }
            if ($request->hasFile('business_license')) {
                $updateData['business_license_path'] = $this->uploadDocument($request->file('business_license'), 'business_license');
            }
            if ($request->hasFile('director_id')) {
                $updateData['director_id_path'] = $this->uploadDocument($request->file('director_id'), 'director_id');
            }

            $client->update($updateData);

            return redirect()->route('client.profile')
                ->with('success', 'Profile updated successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Profile update failed. Please try again.');
        }
    }

    public function vehicles()
    {
        $vehicles = Auth::user()->client->vehicles;
        return view('client.vehicles', compact('vehicles'));
    }

    public function storeVehicle(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'vehicle_type' => 'required|in:truck,van,car,bus,motorcycle,tractor,trailer',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'fuel_type' => 'required|in:diesel,petrol',
            'head_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'trailer_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $client = Auth::user()->client;

            // Upload vehicle documents
            $headCardPath = $this->uploadDocument($request->file('head_card'), 'head_card');
            $trailerCardPath = $request->hasFile('trailer_card') 
                ? $this->uploadDocument($request->file('trailer_card'), 'trailer_card')
                : null;

            // Create vehicle record
            Vehicle::create([
                'client_id' => $client->id,
                'plate_number' => $request->plate_number,
                'vehicle_type' => $request->vehicle_type,
                'make' => $request->make,
                'model' => $request->model,
                'year' => $request->year,
                'fuel_type' => $request->fuel_type,
                'head_card_path' => $headCardPath,
                'trailer_card_path' => $trailerCardPath,
                'status' => Vehicle::STATUS_ACTIVE,
            ]);

            return redirect()->route('client.vehicles')
                ->with('success', 'Vehicle added successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Vehicle addition failed. Please try again.');
        }
    }

    private function uploadDocument($file, $prefix)
    {
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents', $filename, 'public');
        return $path;
    }
}
