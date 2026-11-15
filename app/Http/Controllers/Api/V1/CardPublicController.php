<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Jobs\RecordCardView;
use App\Models\CustomSection;
use App\Models\TemplateCustomization;
use App\Models\User;
use App\Services\AccessService;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class CardPublicController extends Controller
{
    public function show(Request $request, string $username)
    {
        $user = User::with([
            'profile.addresses.city', 'profile.addresses.state', 'profile.addresses.country',
            'profile.primaryaddress.city', 'profile.primaryaddress.state', 'profile.primaryaddress.country',
            'profile.socials.shortlink', 'profile.socials.platform',
            'profile.customlinks.shortlink', 'profile.googlereview.shortlink',
            'shop.products.images',
        ])->where('username', $username)->first();

        if (!$user || !$user->profile) {
            return response()->json(['message' => 'Card not found.'], 404);
        }

        $profile = $user->profile;
        $access = (new AccessService())->resolve($user);
        $isOwner = auth('sanctum')->check() && (int) auth('sanctum')->id() === (int) $user->id;

        // Analytics must never break the card
        try {
            $deviceType = Agent::isDesktop() ? 'Desktop' : (Agent::isTablet() ? 'Tablet' : (Agent::isPhone() ? 'Phone' : 'Other'));
            RecordCardView::dispatch($user->id, $request->ip() ?? '0.0.0.0', [
                'device' => Agent::device(),
                'device_type' => $deviceType,
                'platform' => Agent::platform(),
                'platform_version' => Agent::version(Agent::platform()),
                'browser' => Agent::browser(),
                'browser_version' => Agent::version(Agent::browser()),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        $customization = null;
        if ($isOwner && $request->filled('preview_template')) {
            $templateId = max(1, min(10, (int) $request->preview_template));
            return response()->json($this->payload($user, $profile, $access, null, collect(), 'template', $templateId, true));
        }

        if ($isOwner && $request->filled('customization_id')) {
            $customization = TemplateCustomization::where('id', $request->customization_id)
                ->where('user_id', $user->id)
                ->first();
        }

        $appearanceMode = $profile->appearance_mode ?? 'template';
        if (!$customization && $appearanceMode === 'custom') {
            if ($profile->customization_id) {
                $customization = TemplateCustomization::find($profile->customization_id);
            }
            if (!$customization) {
                $customization = TemplateCustomization::getActiveForUser($user->id);
            }
        }

        $mode = ($customization && ($appearanceMode === 'custom' || ($isOwner && $request->filled('customization_id'))))
            ? 'custom'
            : 'template';

        $templateId = max(1, min(10, (int) ($profile->template_id ?? 1)));
        $sections = CustomSection::getForUser($user->id, $customization?->id);
        $sections->load('teamMembers');

        return response()->json($this->payload($user, $profile, $access, $customization, $sections, $mode, $templateId, $isOwner));
    }

    protected function payload($user, $profile, string $access, $customization, $sections, string $mode, int $templateId, bool $isOwner): array
    {
        $paused = $access === AccessService::LAPSED;
        $watermark = in_array($access, [AccessService::TRIALING, AccessService::LAPSED], true);

        return [
            'access' => $access,
            'paused' => $paused,
            'watermark' => $watermark,
            'is_owner' => $isOwner,
            'mode' => $mode,
            'template_id' => $templateId,
            'card_url' => url('/' . $user->username),
            'download_vcard_url' => url('/download-vcard/' . $user->username),
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'email' => $paused ? null : $user->email,
                'type' => $user->type,
            ],
            'profile' => $paused ? [
                'organization' => null,
                'designation' => null,
                'bio' => null,
                'avatar' => $profile->avatar ? asset($profile->avatar) : null,
                'cover_image' => null,
            ] : [
                'organization' => $profile->organization,
                'designation' => $profile->designation,
                'bio' => $profile->bio,
                'website' => $profile->website,
                'address' => $profile->address,
                'avatar' => $profile->avatar ? asset($profile->avatar) : null,
                'cover_image' => $profile->cover_image ? asset($profile->cover_image) : null,
                'appearance_mode' => $profile->appearance_mode,
                'template_id' => $profile->template_id,
            ],
            'socials' => $paused ? [] : $user->profile->socials->map(function ($s) {
                return [
                    'id' => $s->id,
                    'platform_id' => $s->platform_id,
                    'platform' => $s->platform->name ?? null,
                    'icon' => $s->platform->icon ?? null,
                    'url' => $s->shortlink->shortlink ?? $s->link ?? null,
                ];
            })->values(),
            'shop' => (!$paused && $user->shop) ? [
                'id' => $user->shop->id,
                'name' => $user->shop->name,
                'phone' => $user->shop->phone,
                'products' => $user->shop->products->map(function ($p) {
                    $images = $p->images ?? collect();
                    $primary = $images->first()->path ?? $p->image;
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'description' => $p->description,
                        'original_price' => $p->original_price,
                        'sale_price' => $p->sale_price,
                        'image' => $primary ? asset($primary) : null,
                        'images' => $images->map(fn ($i) => asset($i->path))->values(),
                    ];
                })->values(),
            ] : null,
            'customization' => $customization && !$paused ? [
                'id' => $customization->id,
                'name' => $customization->name,
                'layout_blocks' => $customization->layout_blocks,
                'design_settings' => $customization->getDesignSettingsArray(),
                'custom_css' => TemplateCustomization::sanitizeCustomCss($customization->custom_css),
                'show_bio' => $customization->show_bio ?? true,
                'show_socials' => $customization->show_socials ?? true,
                'show_address' => $customization->show_address ?? true,
                'show_shop' => $customization->show_shop ?? true,
            ] : null,
            'sections' => $paused ? [] : $sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'section_type' => $section->section_type,
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'content' => $section->content,
                    'background_color' => $section->background_color,
                    'text_color' => $section->text_color,
                    'background_image' => $section->background_image ? asset($section->background_image) : null,
                    'team_members' => $section->relationLoaded('teamMembers')
                        ? $section->teamMembers->map(fn ($m) => [
                            'id' => $m->id,
                            'name' => $m->name,
                            'position' => $m->position,
                            'bio' => $m->bio,
                            'avatar' => $m->avatar ? asset($m->avatar) : null,
                        ])->values()
                        : [],
                ];
            })->values(),
        ];
    }
}
