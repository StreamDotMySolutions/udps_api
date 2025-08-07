<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ValidateApiKeyAndStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Get the Bearer Token from Authorization header
        $apiKey = $request->bearerToken();

        if (!$apiKey) {
            return response()->json(['message' => 'API key is required'], 401);
        }

        // Check if the token exists
        $token = PersonalAccessToken::findToken($apiKey);

        if (!$token) {
            return response()->json(['message' => 'Invalid API key'], 401);
        }

        $user = $token->tokenable; // The associated user (tokenable = User model)

        // Check if user is active (status = 1)
        if (!$user || $user->status != 1) {
            return response()->json(['message' => 'User account is inactive or suspended'], 403);
        }

        // Set the user resolver for this request
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
