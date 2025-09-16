<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ApiToken;
use App\Services\EncryptionService;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255'
        ]);

        // Rate limiting
        $key = 'api-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        // Check credentials
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if user is active
        if ($user->status !== User::STATUS_ACTIVE) {
            return response()->json([
                'message' => 'Account is not active'
            ], 403);
        }

        // Generate API token
        $token = ApiToken::create([
            'user_id' => $user->id,
            'name' => $request->device_name ?? 'API Client',
            'token' => EncryptionService::generateApiKey(),
            'last_used_at' => now(),
            'expires_at' => now()->addDays(30)
        ]);

        return response()->json([
            'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes']),
            'token' => $token->token,
            'expires_at' => $token->expires_at
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();

        // Create new token
        $newToken = ApiToken::create([
            'user_id' => $user->id,
            'name' => 'Refreshed Token',
            'token' => EncryptionService::generateApiKey(),
            'last_used_at' => now(),
            'expires_at' => now()->addDays(30)
        ]);

        // Delete old token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'token' => $newToken->token,
            'expires_at' => $newToken->expires_at
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes'])
        ]);
    }

    public function revokeAllTokens(Request $request)
    {
        $user = $request->user();

        ApiToken::where('user_id', $user->id)
            ->where('id', '!=', $request->user()->currentAccessToken()->id)
            ->delete();

        return response()->json([
            'message' => 'All other tokens have been revoked'
        ]);
    }
}
