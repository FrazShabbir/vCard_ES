<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureApiEmailVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && !$user->email_verified_at) {
            return response()->json([
                'message' => 'Please verify your email first.',
                'needs_verification' => true,
            ], 403);
        }

        return $next($request);
    }
}
