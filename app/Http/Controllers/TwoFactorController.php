<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;
use App\Models\User;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
    }

    public function showSetup()
    {
        $user = Auth::user();

        if ($user->two_factor_secret) {
            return redirect()->route('dashboard')->with('info', 'Two-factor authentication is already enabled.');
        }

        $secretKey = $this->google2fa->generateSecretKey();
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );

        return view('auth.two-factor-setup', compact('secretKey', 'qrCodeUrl'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($request->secret, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_secret' => encrypt($request->secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now()
        ]);

        return view('auth.two-factor-enabled', compact('recoveryCodes'))
            ->with('success', 'Two-factor authentication has been enabled successfully!');
    }

    public function showLogin()
    {
        return view('auth.two-factor-login');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $userId = $request->session()->get('two_factor_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $secret = decrypt($user->two_factor_secret);
        $valid = $this->google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            Auth::login($user);
            $request->session()->forget('two_factor_user_id');
            $request->session()->put('two_factor_verified', true);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    public function showRecovery()
    {
        return view('auth.two-factor-recovery');
    }

    public function verifyRecovery(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string'
        ]);

        $userId = $request->session()->get('two_factor_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        if (in_array($request->recovery_code, $recoveryCodes)) {
            $recoveryCodes = array_diff($recoveryCodes, [$request->recovery_code]);

            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes)))
            ]);

            Auth::login($user);
            $request->session()->forget('two_factor_user_id');
            $request->session()->put('two_factor_verified', true);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['recovery_code' => 'Invalid recovery code. Please try again.']);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = Auth::user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Two-factor authentication has been disabled.');
    }

    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8));
        }
        return $codes;
    }
}
