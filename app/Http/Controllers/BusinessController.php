<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            
            if (!Auth::user()->isSuperAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            
            return $next($request);
        });
    }

    public function index()
    {
        $businesses = Business::with(['admin', 'stations', 'clients'])
            ->withCount(['stations', 'clients', 'users'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super-admin.businesses.index', compact('businesses'));
    }

    public function create()
    {
        return view('super-admin.businesses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'email' => 'required|email|unique:businesses,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'notes' => 'nullable|string'
        ]);

        // Create business
        $business = Business::create([
            'name' => $request->name,
            'business_code' => strtoupper(Str::random(6)),
            'registration_number' => $request->registration_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'contact_person' => $request->contact_person,
            'status' => Business::STATUS_APPROVED, // Auto-approve if created by super admin
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'notes' => $request->notes,
        ]);

        // Create business admin user
        $admin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => User::ROLE_ADMIN,
            'business_id' => $business->id,
            'status' => User::STATUS_ACTIVE,
        ]);

        return redirect()->route('super-admin.businesses.index')
            ->with('success', 'Business created successfully. Admin account: ' . $admin->email);
    }

    public function show(Business $business)
    {
        // Simple stats without complex relationships for now
        $stats = [
            'total_stations' => $business->stations()->count(),
            'total_clients' => $business->clients()->count(),
            'total_users' => $business->users()->count(),
            'pending_clients' => $business->clients()->where('registration_status', \App\Models\Client::REGISTRATION_STATUS_PENDING)->count(),
            'monthly_revenue' => 0, // Will implement later
            'monthly_sales' => 0,   // Will implement later
        ];

        return view('super-admin.businesses.show', compact('business', 'stats'));
    }

    public function edit(Business $business)
    {
        return view('super-admin.businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'nullable|string|max:255',
            'email' => 'required|email|unique:businesses,email,' . $business->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,suspended,inactive',
            'notes' => 'nullable|string'
        ]);

        $business->update($request->all());

        return redirect()->route('super-admin.businesses.show', $business)
            ->with('success', 'Business updated successfully.');
    }

    public function approve(Business $business)
    {
        $business->update([
            'status' => Business::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Business approved successfully.');
    }

    public function suspend(Business $business)
    {
        $business->update([
            'status' => Business::STATUS_SUSPENDED,
        ]);

        return redirect()->back()
            ->with('success', 'Business suspended successfully.');
    }

    public function activate(Business $business)
    {
        $business->update([
            'status' => Business::STATUS_APPROVED,
        ]);

        return redirect()->back()
            ->with('success', 'Business activated successfully.');
    }

    public function uploadContract(Request $request, Business $business)
    {
        $request->validate([
            'contract_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $path = $request->file('contract_file')->store('contracts', 'public');

        $business->update([
            'contract_signed' => true,
            'contract_uploaded_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Contract uploaded successfully.');
    }

    public function destroy(Business $business)
    {
        // Soft delete the business
        $business->delete();

        return redirect()->route('super-admin.businesses.index')
            ->with('success', 'Business deleted successfully.');
    }
}
