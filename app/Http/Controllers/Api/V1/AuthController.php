<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AccessService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'account_type' => 'required|in:personal,business',
            'phone' => 'nullable|string|max:30',
            'terms' => 'accepted',
            'marketing_opt_in' => 'nullable|boolean',
            'username' => 'nullable|string|max:60|unique:users,username',
        ]);

        $username = $data['username'] ?? $this->uniqueUsername($data['first_name'], $data['last_name']);

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'username' => $username,
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'type' => $data['account_type'],
                'status' => 1,
                'terms' => true,
                'marketing_opt_in' => (bool) ($data['marketing_opt_in'] ?? false),
            ]);
            $user->assignRole('Member');

            Profile::create([
                'user_id' => $user->id,
                'template_id' => 1,
                'appearance_mode' => 'template',
            ]);

            // Trial starts at verification, not signup — create PENDING row only.
            $plan = Plan::where('profile_type', $data['account_type'])
                ->where('months', 12)
                ->where('is_active', true)
                ->first();

            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan?->id,
                'status' => 'PENDING',
            ]);

            $otp->issue($user, 'email_verify');
            DB::commit();

            Auth::login($user);
            $request->session()->regenerate();
            $token = $user->createToken('nuxt')->plainTextToken;

            return response()->json([
                'user' => $this->userPayload($user),
                'token' => $token,
                'needs_verification' => true,
                'message' => 'Account created. Enter the code we sent (check the server log in local).',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json(['message' => 'Could not create the account.'], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, true)) {
            return response()->json(['message' => 'Those details do not match our records.'], 422);
        }

        $request->session()->regenerate();
        $user = $request->user();
        $token = $user->createToken('nuxt')->plainTextToken;

        return response()->json([
            'user' => $this->userPayload($user),
            'token' => $token,
            'needs_verification' => !$user->email_verified_at,
            'access' => (new AccessService())->payload($user),
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()?->delete();
        }
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Signed out.']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $this->userPayload($user),
            'access' => (new AccessService())->payload($user),
        ]);
    }

    public function verifyOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();
        if (!$otp->verify($user, $data['code'], 'email_verify', true)) {
            return response()->json(['message' => 'That code is wrong or has expired.'], 422);
        }

        $user->email_verified_at = now();
        $user->save();

        // Start trial from verification.
        $sub = Subscription::where('user_id', $user->id)->orderByDesc('id')->first();
        if ($sub && in_array($sub->status, ['PENDING', 'EXPIRED'], true)) {
            $sub->status = 'TRIALING';
            $sub->trial_ends_at = now()->addDays(30);
            $sub->current_period_start = now();
            $sub->current_period_end = now()->addDays(30);
            $sub->save();
        }

        return response()->json([
            'message' => 'Email verified. Your free month has started.',
            'user' => $this->userPayload($user->fresh()),
            'access' => (new AccessService())->payload($user->fresh()),
        ]);
    }

    public function resendOtp(Request $request, OtpService $otp)
    {
        $user = $request->user();
        if ($user->email_verified_at) {
            return response()->json(['message' => 'Email is already verified.']);
        }

        $last = $user->verificationCodes()
            ->where('purpose', 'email_verify')
            ->orderByDesc('id')
            ->first();

        if ($last && $last->created_at->gt(now()->subSeconds(60))) {
            $wait = 60 - $last->created_at->diffInSeconds(now());
            return response()->json(['message' => "Wait {$wait}s before requesting another code."], 429);
        }

        $otp->issue($user, 'email_verify');
        return response()->json(['message' => 'A new code was sent.']);
    }

    public function access(Request $request)
    {
        return response()->json((new AccessService())->payload($request->user()));
    }

    protected function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'username' => $user->username,
            'phone' => $user->phone,
            'type' => $user->type,
            'email_verified_at' => optional($user->email_verified_at)->toIso8601String(),
            'roles' => $user->getRoleNames()->values(),
            'count' => $user->count,
            'reach' => $user->reach,
        ];
    }

    protected function uniqueUsername(string $first, string $last): string
    {
        $base = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($first . '-' . $last)));
        $base = trim($base, '-') ?: 'user';
        $candidate = $base;
        $i = 2;
        while (User::where('username', $candidate)->exists()) {
            $candidate = $base . '-' . $i;
            $i++;
        }
        return $candidate;
    }
}
