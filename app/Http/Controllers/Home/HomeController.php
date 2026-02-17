<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Geolocation;
use App\Models\Profile;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Jenssegers\Agent\Facades\Agent;
use JeroenDesloovere\VCard\VCard;
use Stevebauman\Location\Facades\Location;
use App\Models\Engagement;

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
            'shop.products',
        ])->where('username', $slug)->first();

        if ($user) {
            $profile = $user->profile;
            if (!$profile) {
                abort(404, 'Profile not found');
            }
            try {
                DB::beginTransaction();

                $location_id = null;
                $location_info = config('app.env') === 'local'
                    ? Location::get('182.185.236.113')
                    : Location::get($request->ip());

                if ($location_info != null) {
                    $findIP = Geolocation::where('user_id', $user->id)->where('ip_address', $request->ip())->first();


                    if ($findIP) {
                        $location_id = $findIP->id;
                        $is_unique_location = false;
                    }
                    if (!$findIP) {

                        $location = Geolocation::create([
                            'user_id' => $user->id,
                            'ip_address' => $request->ip(),
                            'country_name' => $location_info->countryName,
                            'country_code' => $location_info->countryCode,
                            'region_code' => $location_info->regionCode,
                            'region_name' => $location_info->regionName,
                            'city_name' => $location_info->cityName,
                            'zip_code' => $location_info->zipCode,
                            'iso_code' => $location_info->isoCode,
                            'postal_code' => $location_info->postalCode,
                            'latitude' => $location_info->latitude,
                            'longitude' => $location_info->longitude,
                            'metro_code' => $location_info->metroCode,
                            'area_code' => $location_info->areaCode,
                            'timezone' => $location_info->timezone,
                            'device' => Agent::device(),
                            'platform' => Agent::platform(),
                            'platform_version' => Agent::version(Agent::platform()),
                            'browser' => Agent::browser(),
                            'browser_version' => Agent::version(Agent::browser()),
                        ]);
                        $location_id = $location->id;
                        $is_unique_location = true;
                    }

                }

                if ($location_id !== null) {
                    $findDevice = Device::where('user_id', $user->id)->where('ip_address', $request->ip())
                        ->where('geolocation_id', $location_id)->where('device', Agent::device())->where('platform', Agent::platform())->first();
                    if (!$findDevice) {
                        $deviceType = Agent::isDesktop() ? 'Desktop' : (Agent::isTablet() ? 'Tablet' : (Agent::isPhone() ? 'Phone' : 'Other'));
                        $device = Device::create([
                            'user_id' => $user->id,
                            'geolocation_id' => $location_id,
                            'ip_address' => $request->ip(),
                            'device' => Agent::device(),
                            'device_type' => $deviceType,
                            'platform' => Agent::platform(),
                            'platform_version' => Agent::version(Agent::platform()),
                            'browser' => Agent::browser(),
                            'browser_version' => Agent::version(Agent::browser()),
                        ]);
                        $user->increment('reach');
                        $is_unique_device = true;
                    } else {
                        $device = $findDevice;
                        $is_unique_device = false;
                    }
                    Engagement::create([
                        'user_id' => $user->id,
                        'geolocation_id' => $location_id,
                        'device_id' => $device->id,
                        'is_unique_location' => $is_unique_location,
                        'is_unique_device' => $is_unique_device,
                    ]);
                }

                $user->increment('count');
                DB::commit();

                $profileUrl = url()->current();
                $avatarUrl = $this->profileAvatarUrl($profile, $user);
                $viewData = compact('user', 'profile', 'profileUrl', 'avatarUrl');

                if (auth()->check() && (int) auth()->user()->id === (int) $user->id && $request->filled('preview_template')) {
                    $templateId = max(1, min(10, (int) $request->preview_template));
                } else {
                    $templateId = (int) ($profile->template_id ?? 1);
                    $templateId = max(1, min(10, $templateId));
                }
                $viewName = 'frontend.pages.cards.templates.template' . $templateId;

                return view($viewName, $viewData)->with('extra_class', 'd-none');
            } catch (\Throwable $th) {
                DB::rollback();
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
