<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfile
{
    /**
     * Soft gate: nudge incomplete member profiles through onboarding
     * before accessing secondary features (shop, physical card, etc.).
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if (!$user || !$user->profile) {
            return $next($request);
        }

        if ($request->session()->get('onboarding_completed')) {
            return $next($request);
        }

        $profile = $user->profile;
        $hasBio = filled($profile->bio);
        $hasOrgOrAddress = filled($profile->organization) || filled($profile->address);

        if (!$hasBio || !$hasOrgOrAddress) {
            if (!$request->routeIs('user.onboarding*') && !$request->routeIs('user.profile*')) {
                return redirect()->route('user.onboarding')
                    ->with('info', 'Finish a few setup steps to get the most from your card.');
            }
        }

        return $next($request);
    }
}
