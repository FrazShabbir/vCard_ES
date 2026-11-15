<?php

namespace App\Http\Middleware;

use App\Services\AccessService;
use Closure;
use Illuminate\Http\Request;

class EnsureEntitled
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (!(new AccessService())->isEntitled($user)) {
            return response()->json([
                'message' => 'Your plan has paused. Renew to edit this.',
                'access' => AccessService::LAPSED,
            ], 403);
        }

        return $next($request);
    }
}
