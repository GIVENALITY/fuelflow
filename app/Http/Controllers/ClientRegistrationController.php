<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('client-registration.simple');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:clients,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create user account
            $user = User::create([
                'name' => $request->company_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_CLIENT,
                'status' => 'active',
            ]);

            // Upload documents
            $tinDocumentPath = $this->uploadDocument($request->file('tin_document'), 'tin');
            $brelaCertificatePath = $this->uploadDocument($request->file('brela_certificate'), 'brela');
            $businessLicensePath = $this->uploadDocument($request->file('business_license'), 'business_license');
            $directorIdPath = $this->uploadDocument($request->file('director_id'), 'director_id');

            // Create client record
            $client = Client::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'tax_id' => $request->tax_id,
                'tin_document_path' => $tinDocumentPath,
                'brela_certificate_path' => $brelaCertificatePath,
                'business_license_path' => $businessLicensePath,
                'director_id_path' => $directorIdPath,
                'registration_status' => Client::REGISTRATION_STATUS_PENDING,
                'status' => Client::STATUS_INACTIVE,
                'credit_limit' => 0,
                'current_balance' => 0,
            ]);

            // Upload vehicle documents
            $headCardPath = $this->uploadDocument($request->file('head_card'), 'head_card');
            $trailerCardPath = $request->hasFile('trailer_card') 
                ? $this->uploadDocument($request->file('trailer_card'), 'trailer_card')
                : null;

            // Create vehicle record
            Vehicle::create([
                'client_id' => $client->id,
                'plate_number' => $request->vehicle_plate_number,
                'vehicle_type' => $request->vehicle_type,
                'make' => $request->vehicle_make,
                'model' => $request->vehicle_model,
                'year' => $request->vehicle_year,
                'fuel_type' => $request->vehicle_fuel_type,
                'head_card_path' => $headCardPath,
                'trailer_card_path' => $trailerCardPath,
                'status' => Vehicle::STATUS_ACTIVE,
            ]);

            return redirect()->route('client-registration.success')
                ->with('success', 'Registration submitted successfully! Your application is pending approval.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    public function success()
    {
        return view('client-registration.success');
    }

    private function uploadDocument($file, $type)
    {
        $filename = $type . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/' . $type, $filename, 'public');
        return $path;
    }
}
