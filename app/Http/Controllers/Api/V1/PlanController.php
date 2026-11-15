<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)->orderBy('profile_type')->orderBy('months')->get()
            ->map(fn (Plan $p) => [
                'id' => $p->id,
                'key' => $p->key,
                'name' => $p->name,
                'profile_type' => $p->profile_type,
                'months' => $p->months,
                'price_paisa' => $p->price_paisa,
                'price_label' => formatPkr($p->price_paisa),
                'per_month_label' => formatPkr((int) round($p->price_paisa / max(1, $p->months))),
                'badge' => $p->badge,
            ]);

        return response()->json(['plans' => $plans]);
    }
}
