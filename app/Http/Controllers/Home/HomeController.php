<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Jobs\RecordCardView;
use App\Models\Profile;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Jenssegers\Agent\Facades\Agent;
use JeroenDesloovere\VCard\VCard;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.pages.index');
    }
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function about()
    {
        return view('frontend.pages.about');
    }

    public function privacy()
    {
        return view('frontend.pages.privacy');
    }

    public function slug(Request $request, $slug)
    {
        $user = User::with([
            'profile.addresses.city', 'profile.addresses.state', 'profile.addresses.country',
            'profile.primaryaddress.city', 'profile.primaryaddress.state', 'profile.primaryaddress.country',
            'profile.socials.shortlink', 'profile.socials.platform',
            'profile.customlinks.shortlink', 'profile.googlereview.shortlink',
            'shop.products.images',
        ])->where('username', $slug)->first();

        if ($user) {
            $profile = $user->profile;
            if (!$profile) {
                abort(404, 'Profile not found');
            }
            try {
                // Analytics off the request path (sync when QUEUE_CONNECTION=sync)
                $deviceType = Agent::isDesktop() ? 'Desktop' : (Agent::isTablet() ? 'Tablet' : (Agent::isPhone() ? 'Phone' : 'Other'));
                RecordCardView::dispatch($user->id, $request->ip(), [
                    'device' => Agent::device(),
                    'device_type' => $deviceType,
                    'platform' => Agent::platform(),
                    'platform_version' => Agent::version(Agent::platform()),
                    'browser' => Agent::browser(),
                    'browser_version' => Agent::version(Agent::browser()),
                ]);

                $profileUrl = url()->current();
                $avatarUrl = $this->profileAvatarUrl($profile, $user);

                $isOwner = auth()->check() && (int) auth()->user()->id === (int) $user->id;

                // Template gallery preview: owner ?preview_template=N skips customization entirely
                if ($isOwner && $request->filled('preview_template')) {
                    $templateId = max(1, min(10, (int) $request->preview_template));
                    $sections = collect();
                    $customization = null;
                    $viewData = compact('user', 'profile', 'profileUrl', 'avatarUrl', 'customization', 'sections');
                    return view('frontend.pages.cards.templates.template' . $templateId, $viewData)
                        ->with('extra_class', 'd-none');
                }

                // Builder preview: owner may force a specific draft/active customization
                $customization = null;
                if ($isOwner && $request->filled('customization_id')) {
                    $customization = \App\Models\TemplateCustomization::where('id', $request->customization_id)
                        ->where('user_id', $user->id)
                        ->first();
                }

                $appearanceMode = $profile->appearance_mode ?? 'template';

                // Live card: Template mode vs Custom design mode
                if (!$customization && $appearanceMode === 'custom') {
                    if ($profile->customization_id) {
                        $customization = \App\Models\TemplateCustomization::find($profile->customization_id);
                    }
                    if (!$customization) {
                        $customization = \App\Models\TemplateCustomization::getActiveForUser($user->id);
                    }
                }

                $customizationId = $customization ? $customization->id : null;
                $sections = \App\Models\CustomSection::getForUser($user->id, $customizationId);
                $sections->load('teamMembers');

                $viewData = compact('user', 'profile', 'profileUrl', 'avatarUrl', 'customization', 'sections');

                // Custom mode or builder preview with a customization → dynamic view
                if ($customization && ($appearanceMode === 'custom' || ($isOwner && $request->filled('customization_id')))) {
                    return view('frontend.pages.cards.dynamic', $viewData)->with('extra_class', 'd-none');
                }

                $templateId = (int) ($profile->template_id ?? 1);
                $templateId = max(1, min(10, $templateId));
                return view('frontend.pages.cards.templates.template' . $templateId, $viewData)
                    ->with('extra_class', 'd-none');
            } catch (\Throwable $th) {
                throw $th;
            }

        } else {
            if (auth()->user()) {
                // dd('hello');
                Auth::guard('web')->logout();
                $request->session()->invalidate();
            }
            return view('frontend.pages.auth.register')
                ->with('slug', $slug)
                ->with('extra_class', '');
        }
    }

    public function profileEdit($slug)
    {
        if (!auth()->user()) {
            return redirect()->route('login.user');
        } else {
            return redirect()->route('user.profile');
        }
    }

    public function profileUpdate(Request $request, $slug)
    {
        $user = User::where('username', $slug)->first();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed', Rules\Password::defaults(),
            'address' => 'required',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'google' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'pinterest' => 'nullable|string|max:255',
        ]);

        if ($user) {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->bio = $request->bio;
            $user->organization = $request->organization;
            $user->designation = $request->designation;

            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->instagram = $request->instagram;
            $user->twitter = $request->twitter;
            $user->facebook = $request->facebook;
            $user->google = $request->google;
            $user->linkedin = $request->linkedin;
            $user->youtube = $request->youtube;
            $user->tiktok = $request->tiktok;
            $user->pinterest = $request->pinterest;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            if ($request->avatar) {
                $request->validate([
                    'avatar' => 'image|mimes:jpeg,png,jpg|max:2048',
                ]);
                $file = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $filename = getRandomString() . '-' . time() . '.' . $extension;
                $file->move('uploads/avatars', $filename);
                $path = 'uploads/avatars/' . $filename;
                $user->avatar = $path;
            }
            if ($request->cover_image) {
                $request->validate([
                    'cover_image' => 'mimes:jpeg,png,jpg|max:2048',
                ]);
                $file = $request->file('cover_image');
                $extension = $file->getClientOriginalExtension();
                $filename = getRandomString() . '-' . time() . '.' . $extension;
                $file->move('uploads/cover_images', $filename);
                $background = 'uploads/cover_images/' . $filename;
                $user->cover_image = $background;
            }

            $user->save();
            return redirect()->route('slug', $slug);
        } else {
            return redirect()->route('slug', $slug);
        }
    }

    public function userLogin()
    {
        return view('frontend.pages.auth.login');
    }

    public function downloadVCard($id)
    {
        try {
            $user = User::with(['profile', 'profile.socials.shortlink', 'profile.socials.platform'])->where('username', $id)->firstOrFail();
            $profile = $user->profile;

            $vcard = new VCard();
            $vcard->addName($user->last_name ?? '', $user->first_name ?? '', '', '', '');
            if ($profile) {
                if (!empty($profile->organization)) {
                    $vcard->addCompany($profile->organization);
                }
                if (!empty($profile->designation)) {
                    $vcard->addJobtitle($profile->designation);
                    $vcard->addRole($profile->designation);
                }
                if (!empty($profile->address)) {
                    $vcard->addAddress(null, null, $profile->address, null, null, null, null);
                }
                if (!empty($profile->website)) {
                    $vcard->addURL($profile->website);
                }
            }
            if (!empty($user->email)) {
                $vcard->addEmail($user->email);
            }
            if (!empty($user->phone)) {
                $vcard->addPhoneNumber($user->phone, 'PREF;WORK');
            }
            if ($profile && $profile->relationLoaded('socials')) {
                foreach ($profile->socials as $social) {
                    $link = $social->shortlink?->shortlink ?? $social->shortlink?->link ?? null;
                    if ($link) {
                        $vcard->addURL($link, 'TYPE=' . ($social->platform->name ?? 'URL'));
                    }
                }
            }
            $avatarUrl = $profile && !empty($profile->avatar)
                ? (str_starts_with($profile->avatar, 'http') ? $profile->avatar : url($profile->avatar))
                : 'https://ui-avatars.com/api/?name=' . urlencode(($user->first_name ?? '') . '+' . ($user->last_name ?? '')) . '&background=0D8ABC&color=fff';
            $vcard->addPhoto($avatarUrl);

            $filename = preg_replace('/[^a-zA-Z0-9_-]/', '-', ($user->first_name ?? '') . '-' . ($user->last_name ?? ''));
            $filename = substr($filename ?: 'contact', 0, 64);
            $output = $vcard->buildVCard();

            return response($output, 200, [
                'Content-Type' => 'text/vcard; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.vcf"',
                'Content-Length' => (string) strlen($output),
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'public',
            ]);
        } catch (\Throwable $th) {
            alert()->error('Error', 'Something went wrong!');
            return redirect()->back();
        }
    }

    /**
     * Resolve profile avatar URL (local asset or external default).
     */
    private function profileAvatarUrl(Profile $profile, User $user): string
    {
        $avatar = $profile->avatar;
        if (empty($avatar)) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . '+' . $user->last_name) . '&background=0D8ABC&color=fff&size=400';
        }
        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            return $avatar;
        }
        return asset($avatar);
    }

    public function shortlinkOpener($slug)
    {
        $shortlink = ShortLink::where('slug', $slug)->first();
        if ($shortlink) {
            $shortlink->count = $shortlink->count + 1;
            $shortlink->save();
            return redirect($shortlink->link);
        } else {
            return redirect()->route('home');
        }
    }
}
