<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class RateLimiting
{
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        // Different rate limits for different actions
        if ($request->is('login') || $request->is('two-factor/*')) {
            $maxAttempts = 5;
            $decayMinutes = 15;
        } elseif ($request->is('fuel-requests/create') || $request->is('fuel-requests/*/approve')) {
            $maxAttempts = 10;
            $decayMinutes = 1;
        } elseif ($request->is('api/*')) {
            $maxAttempts = 100;
            $decayMinutes = 1;
        }

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Too many attempts. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', RateLimiter::remaining($key, $maxAttempts));
        $response->headers->set('X-RateLimit-Reset', now()->addSeconds($decayMinutes * 60)->timestamp);

        return $response;
    }

    protected function resolveRequestSignature(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return 'user:' . $user->id . ':' . $request->ip();
        }

        return 'ip:' . $request->ip();
    }
}
