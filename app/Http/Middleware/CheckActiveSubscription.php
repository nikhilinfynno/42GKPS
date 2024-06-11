<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
                'errors' => ['error' => 'Not authenticated'],
            ], 401);
        }

        // Check if the user has an active subscription that has not expired
        $subscriptionIsActive = $user->activeSubscription()
            ->where('is_active', 1)  //'status' field denotes active status
            ->where('expires_at', '>', now())  // 'expires_at' field denotes the expiry date
            ->exists();
        if (!$subscriptionIsActive) {
            // User does not have an active subscription
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
                'errors' => ['error'=>'No active subscription found or subscription has expired'],
            ], 403);
        }

        return $next($request);
    }
}
