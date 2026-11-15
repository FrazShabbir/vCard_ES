<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = $user->profile;
        $isBusiness = $user->type === 'business';

        $steps = [
            [
                'key' => 'profile',
                'title' => 'Complete your profile',
                'description' => 'Add a bio, organization, and address so visitors know who you are.',
                'route' => 'user.profile',
                'done' => $this->profileComplete($profile),
            ],
            [
                'key' => 'appearance',
                'title' => 'Set your appearance',
                'description' => 'Choose a template or build a custom page for your live card.',
                'route' => 'user.appearance',
                'done' => (bool) ($profile->template_id || $profile->customization_id),
            ],
            [
                'key' => 'socials',
                'title' => 'Add social links',
                'description' => 'Connect Instagram, LinkedIn, WhatsApp, and more.',
                'route' => 'user.socials',
                'done' => $profile->socials()->exists(),
            ],
        ];

        if ($isBusiness) {
            $steps[] = [
                'key' => 'shop',
                'title' => 'Set up your shop',
                'description' => 'Create a shop and products so visitors can order via WhatsApp.',
                'route' => 'user.shop',
                'done' => $user->shop()->exists(),
            ];
        }

        $allDone = collect($steps)->every(fn ($s) => $s['done']);
        $next = collect($steps)->first(fn ($s) => !$s['done']);

        return view('user.onboarding.index', compact('steps', 'allDone', 'next', 'isBusiness', 'user'));
    }

    public function complete(Request $request)
    {
        $request->session()->put('onboarding_completed', true);
        return redirect()->route('member.dashboard');
    }

    public function skip(Request $request)
    {
        $request->session()->put('onboarding_completed', true);
        return redirect()->route('member.dashboard');
    }

    protected function profileComplete($profile): bool
    {
        if (!$profile) {
            return false;
        }
        $hasBio = filled($profile->bio);
        $hasOrgOrAddress = filled($profile->organization) || filled($profile->address);
        return $hasBio && $hasOrgOrAddress;
    }
}
