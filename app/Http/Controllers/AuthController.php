<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if 2FA is enabled (only for admin, manager, treasury roles)
            if (
                in_array($user->role, [User::ROLE_ADMIN, User::ROLE_STATION_MANAGER, User::ROLE_TREASURY]) &&
                $user->two_factor_secret && $user->two_factor_confirmed_at
            ) {
                // Store user ID in session for 2FA verification
                $request->session()->put('two_factor_user_id', $user->id);
                Auth::logout(); // Logout temporarily

                return redirect()->route('two-factor.login');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
