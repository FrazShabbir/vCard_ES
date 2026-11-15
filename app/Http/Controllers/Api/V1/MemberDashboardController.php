<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AccessService;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function overview(Request $request)
    {
        $user = $request->user()->load('profile');
        $access = (new AccessService())->payload($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'type' => $user->type,
            ],
            'access' => $access,
            'metrics' => [
                'reads' => (int) $user->count,
                'reach' => (int) $user->reach,
                'devices' => $user->devices()->count(),
                'locations' => $user->locations()->count(),
            ],
            'card' => [
                'url' => url('/' . $user->username),
                'appearance_mode' => $user->profile->appearance_mode ?? 'template',
                'template_id' => $user->profile->template_id ?? 1,
                'live_label' => ($user->profile->appearance_mode ?? 'template') === 'custom'
                    ? 'Custom design'
                    : 'Template ' . ($user->profile->template_id ?? 1),
            ],
            'qr_download_url' => route('downloadQRCode'),
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        $locations = $user->locations()
            ->select('id', 'city_name', 'region_name', 'country_name', 'created_at')
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        $devices = $user->devices()
            ->select('id', 'device', 'device_type', 'platform', 'browser', 'created_at')
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return response()->json([
            'summary' => [
                'reads' => (int) $user->count,
                'reach' => (int) $user->reach,
                'note' => 'Visitor matching is approximate (about 80% accurate).',
            ],
            'people_locations' => $locations,
            'devices' => $devices,
        ]);
    }

    public function profileShow(Request $request)
    {
        $user = $request->user()->load('profile');
        return response()->json([
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'username' => $user->username,
                'type' => $user->type,
                'username_locked' => (bool) $user->username_locked,
            ],
            'profile' => $user->profile,
        ]);
    }

    public function profileUpdate(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'phone' => 'nullable|string|max:30',
            'bio' => 'nullable|string|max:2000',
            'organization' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $user->fill(collect($data)->only(['first_name', 'last_name', 'phone'])->all());
        $user->save();

        $profile = $user->profile;
        $profile->fill(collect($data)->only(['bio', 'organization', 'designation', 'website', 'address'])->all());
        $profile->save();

        return response()->json(['message' => 'Profile saved.', 'profile' => $profile->fresh()]);
    }

    public function appearanceShow(Request $request)
    {
        $user = $request->user()->load('profile');
        $customs = \App\Models\TemplateCustomization::where('user_id', $user->id)
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->get(['id', 'name', 'is_active', 'updated_at']);

        return response()->json([
            'appearance_mode' => $user->profile->appearance_mode ?? 'template',
            'template_id' => (int) ($user->profile->template_id ?? 1),
            'customization_id' => $user->profile->customization_id,
            'customizations' => $customs,
            'preview_base' => url('/' . $user->username),
        ]);
    }

    public function appearanceMode(Request $request)
    {
        $data = $request->validate([
            'appearance_mode' => 'required|in:template,custom',
            'template_id' => 'nullable|integer|min:1|max:10',
        ]);

        $profile = $request->user()->profile;
        $profile->appearance_mode = $data['appearance_mode'];
        if (!empty($data['template_id'])) {
            $profile->template_id = $data['template_id'];
        }
        $profile->save();

        return response()->json(['message' => 'Appearance updated.', 'appearance_mode' => $profile->appearance_mode]);
    }
}
