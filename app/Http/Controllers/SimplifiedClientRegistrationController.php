<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SimplifiedClientRegistrationController extends Controller
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

            // Create basic client record
            $client = Client::create([
                'user_id' => $user->id,
                'company_name' => $request->company_name,
                'email' => $request->email,
                'registration_status' => Client::REGISTRATION_STATUS_PENDING,
                'status' => Client::STATUS_INACTIVE,
                'credit_limit' => 0,
                'current_balance' => 0,
            ]);

            // Auto-login the user
            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', 'Account created successfully! Please complete your profile to continue.');
                
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('client-registration.success');
    }
}
