<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRegistrationRequest;
use App\Models\Profile;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Jenssegers\Agent\Facades\Agent;
use Stevebauman\Location\Facades\Location;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        abort(404);
        return view('frontend.pages.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \App\Http\Requests\Auth\StoreRegistrationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $username = strtolower(str_replace([' ', '_'], '-', $request->slug));
            $username = preg_replace('/-+/', '-', trim($username, '-'));

            $existing = User::where('username', $username)->first();
            if ($existing) {
                $username = $username . '-' . Str::lower(Str::random(3));
            }

            $avatarPath = 'default/avatar/default.png';
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $imageName = getRandomString() . '-' . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/avatars');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $img = Image::make($image->path());
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $imageName);
                $originalPath = public_path('uploads/avatars/original');
                if (!is_dir($originalPath)) {
                    mkdir($originalPath, 0755, true);
                }
                $image->move($originalPath, $imageName);
                $avatarPath = 'uploads/avatars/' . $imageName;
            }

            $background = 'default/cover/placeholder.png';
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $imageNameBg = getRandomString() . '-' . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/cover_images');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $img = Image::make($image->path());
                $img->resize(1200, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $imageNameBg);
                $originalPath = public_path('uploads/cover_images/original');
                if (!is_dir($originalPath)) {
                    mkdir($originalPath, 0755, true);
                }
                $image->move($originalPath, $imageNameBg);
                $background = 'uploads/cover_images/' . $imageNameBg;
            }

            $referral_code = generateAlpha(3) . generateAlpha(3);
            while (User::where('referral_code', $referral_code)->exists()) {
                $referral_code = generateAlpha(2) . generateAlpha(4);
            }

            $referrer = $request->filled('referral_code')
                ? User::where('referral_code', $request->referral_code)->first()
                : null;

            $location_info = config('app.env') === 'local'
                ? Location::get('182.185.236.113')
                : Location::get($request->ip());

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'referral_code' => $referral_code,
                'referral_code_entered' => $request->referral_code,
                'refer_by_id' => $referrer?->id,
                'username' => $username,
                'phone' => $request->phone,
                'email' => $request->email,
                'terms' => 1,
                'password' => Hash::make($request->password),
                'status' => 1,
                'type' => $request->account_type,
                'expiry' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'country_name' => $location_info?->countryName,
                'city_name' => $location_info?->cityName,
                'zip_code' => $location_info?->zipCode,
                'region_name' => $location_info?->regionName,
                'device' => Agent::device(),
                'platform' => Agent::platform(),
                'platform_version' => Agent::version(Agent::platform()),
                'browser' => Agent::browser(),
                'browser_version' => Agent::version(Agent::browser()),
            ]);

            $profile = Profile::create([
                'user_id' => $user->id,
                'bio' => $request->bio,
                'organization' => $request->organization,
                'designation' => $request->designation,
                'avatar' => $avatarPath,
                'cover_image' => $background,
                'website' => $request->website,
                'address' => $request->address,
            ]);

            $user->assignRole('Member');
            event(new Registered($user));
            DB::commit();
            info('User Registered', [
                'user' => $user,
                'profile' => $profile,
            ]);
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        } catch (\Throwable $th) {
            DB::rollback();
            info($th);
            alert()->error('Error', $th->getMessage());
            return redirect()->back();
        }
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'username' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);
    //     $username = str_replace(' ', '-', $request->username);
    //     // check username exist
    //     $usernamefind = User::where('username', $username)->first();
    //     if ($usernamefind) {
    //         $username = $username.'-'.str_random(2);
    //     }
    //     $user = User::create([
    //         'first_name' => $request->first_name,
    //         'last_name' => $request->last_name,
    //         'username' => $username,
    //         'status' => $request->status,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));
    //     Auth::login($user);
    //     return redirect(RouteServiceProvider::HOME);
    // }

}

// 'instagram' => 'nullable|string|max:255',
// 'twitter' => 'nullable|string|max:255',
// 'facebook' => 'nullable|string|max:255',
// 'google' => 'nullable|string|max:255',
// 'linkedin' => 'nullable|string|max:255',
// 'youtube' => 'nullable|string|max:255',
// 'tikTok' => 'nullable|string|max:255',
// 'pinterest' => 'nullable|string|max:255',

// 'instagram' => $request->instagram,
// 'twitter' => $request->twitter,
// 'facebook' => $request->facebook,
// 'google' => $request->google,
// 'linkedin' => $request->linkedin,
// 'youtube' => $request->youtube,
// 'tiktok' => $request->tiktok,
// 'pinterest' => $request->pinterest,

// $file = $request->file('cover_image');
// $extension = $file->getClientOriginalExtension();
// $filename = getRandomString().'-'.time() . '.' . $extension;
// $file->move('uploads/cover_images', $filename);

// $file = $request->file('avatar');
// $extension = $file->getClientOriginalExtension();
// $filename = getRandomString().'-'.time() . '.' . $extension;
// $file->move('uploads/avatars', $filename);
// $path = 'uploads/avatars/'.$filename;
