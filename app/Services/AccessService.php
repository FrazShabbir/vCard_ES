<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class AccessService
{
    public const TRIALING = 'trialing';
    public const ACTIVE = 'active';
    public const LAPSED = 'lapsed';

    /**
     * Resolve access state. Fails closed: no usable subscription → lapsed.
     */
    public function resolve(User $user): string
    {
        $sub = Subscription::where('user_id', $user->id)
            ->whereIn('status', ['TRIALING', 'ACTIVE', 'PAST_DUE'])
            ->orderByDesc('id')
            ->first();

        if (!$sub) {
            return self::LAPSED;
        }

        if ($sub->status === 'TRIALING') {
            if ($sub->trial_ends_at && Carbon::now()->greaterThan($sub->trial_ends_at)) {
                $sub->status = 'EXPIRED';
                $sub->save();
                return self::LAPSED;
            }
            return self::TRIALING;
        }

        if ($sub->status === 'ACTIVE' || $sub->status === 'PAST_DUE') {
            if ($sub->current_period_end && Carbon::now()->greaterThan($sub->current_period_end)) {
                $sub->status = 'EXPIRED';
                $sub->save();
                return self::LAPSED;
            }
            return self::ACTIVE;
        }

        return self::LAPSED;
    }

    public function isEntitled(User $user): bool
    {
        $state = $this->resolve($user);
        return in_array($state, [self::TRIALING, self::ACTIVE], true);
    }

    public function payload(User $user): array
    {
        $state = $this->resolve($user);
        $sub = Subscription::with('plan')->where('user_id', $user->id)->orderByDesc('id')->first();

        return [
            'access' => $state,
            'entitled' => $this->isEntitled($user),
            'email_verified' => (bool) $user->email_verified_at,
            'subscription' => $sub ? [
                'id' => $sub->id,
                'status' => $sub->status,
                'trial_ends_at' => optional($sub->trial_ends_at)->toIso8601String(),
                'current_period_end' => optional($sub->current_period_end)->toIso8601String(),
                'plan' => $sub->plan ? [
                    'id' => $sub->plan->id,
                    'key' => $sub->plan->key,
                    'name' => $sub->plan->name,
                    'profile_type' => $sub->plan->profile_type,
                    'months' => $sub->plan->months,
                    'price_paisa' => $sub->plan->price_paisa,
                    'price_label' => formatPkr($sub->plan->price_paisa),
                ] : null,
            ] : null,
        ];
    }
}
