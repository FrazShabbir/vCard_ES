<?php

namespace App\Http\Controllers;

use App\Models\SocialLink;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Redirect to role-specific dashboard.
     */
    public function dashboard()
    {
        if (Auth::user()->hasRole('Member')) {
            return redirect()->route('member.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }

    /**
     * Build a 12-month array (last 12 months) with optional fill from query result keyed by month name.
     */
    private function buildMonthBuckets(Carbon $end, ?Collection $data = null): array
    {
        $start = $end->copy()->subMonths(11);
        $buckets = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $start->copy()->addMonths($i)->format('F');
            $buckets[$month] = $data ? ($data->get($month, 0)) : 0;
        }
        return $buckets;
    }

    /**
     * Safe percentage of count against total (avoids division by zero).
     */
    private function percentage(float $count, float $total): float
    {
        return $total > 0 ? ($count / $total) * 100 : 0;
    }

    /**
     * Member dashboard: analytics for the authenticated user.
     */
    public function memberDashboard()
    {
        $user = Auth::user();
        $user->loadMissing(['profile', 'vcard']);

        $end = Carbon::now();
        $rangeStart = $end->copy()->subMonths(11)->startOfMonth();
        $rangeEnd = $end->copy()->endOfMonth();

        $engagements = $this->buildMonthBuckets($end,
            $user->engagements()
                ->selectRaw('count(*) as count, monthname(created_at) as month')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->groupBy(DB::raw('monthname(created_at)'))
                ->pluck('count', 'month')
        );
        $locations = $this->buildMonthBuckets($end,
            $user->locations()
                ->selectRaw('count(*) as count, monthname(created_at) as month')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->groupBy(DB::raw('monthname(created_at)'))
                ->pluck('count', 'month')
        );
        $devices = $this->buildMonthBuckets($end,
            $user->devices()
                ->selectRaw('count(*) as count, monthname(created_at) as month')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->groupBy(DB::raw('monthname(created_at)'))
                ->pluck('count', 'month')
        );

        $platforms = $user->devices()
            ->selectRaw('platform, count(*) as count')
            ->groupBy('platform')
            ->pluck('count', 'platform');
        $platform_total = $platforms->sum();
        $platforms_percentages = $platform_total > 0
            ? $platforms->map(fn ($count) => $this->percentage($count, $platform_total))
            : collect();

        $devices_count = $user->devices()
            ->selectRaw('device, count(*) as count')
            ->groupBy('device')
            ->pluck('count', 'device');
        $device_total = $devices_count->sum();
        $devices_percentages = $device_total > 0
            ? $devices_count->map(fn ($count) => $this->percentage($count, $device_total))
            : collect();

        $clients = $user->devices()
            ->selectRaw('device_type, count(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type');
        $client_total = $clients->sum();
        $clients_percentages = $client_total > 0
            ? $clients->map(fn ($count) => $this->percentage($count, $client_total))
            : collect();

        $locationsNames = $user->locations()
            ->selectRaw('country_name, count(*) as count')
            ->groupBy('country_name')
            ->pluck('count', 'country_name');
        $locations_total = $locationsNames->sum();
        $locations_percentages = $locations_total > 0
            ? $locationsNames->map(fn ($count) => $this->percentage($count, $locations_total))
            : collect();

        $profileId = $user->profile?->id;
        $socialLinks = $profileId
            ? SocialLink::with('shortlink')->where('profile_id', $profileId)->get()
            : collect();
        $linksCount = $profileId
            ? (int) DB::table('social_links')
                ->join('short_links', 'social_links.short_link_id', '=', 'short_links.id')
                ->where('social_links.profile_id', $profileId)
                ->sum('short_links.count')
            : 0;

        return view('user.dashboard.dashboard', compact(
            'user',
            'engagements',
            'locations',
            'devices',
            'socialLinks',
            'linksCount',
            'platforms',
            'platforms_percentages',
            'platform_total',
            'devices_count',
            'devices_percentages',
            'device_total',
            'clients',
            'clients_percentages',
            'client_total',
            'locationsNames',
            'locations_percentages',
            'locations_total'
        ));
    }

    /**
     * Admin dashboard: site-wide analytics.
     */
    public function adminDashboard()
    {
        $end = Carbon::now();
        $rangeStart = $end->copy()->subMonths(11)->startOfMonth();
        $rangeEnd = $end->copy()->endOfMonth();

        $totalReach = (int) DB::table('users')->sum('reach');
        $totalCount = (int) DB::table('users')->sum('count');
        $totalDevices = (int) DB::table('devices')->count();
        $totalLocations = (int) DB::table('geolocations')->count();

        $engagements = $this->buildMonthBuckets($end,
            DB::table('engagements')
                ->selectRaw('count(*) as count, monthname(created_at) as month')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->groupBy(DB::raw('monthname(created_at)'))
                ->pluck('count', 'month')
        );
        $locations = $this->buildMonthBuckets($end,
            DB::table('geolocations')
                ->selectRaw('count(*) as count, monthname(created_at) as month')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->groupBy(DB::raw('monthname(created_at)'))
                ->pluck('count', 'month')
        );
        $devices = $this->buildMonthBuckets($end,
            DB::table('devices')
                ->selectRaw('count(*) as count, monthname(created_at) as month')
                ->whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->groupBy(DB::raw('monthname(created_at)'))
                ->pluck('count', 'month')
        );

        $platforms = DB::table('devices')
            ->selectRaw('platform, count(*) as count')
            ->groupBy('platform')
            ->pluck('count', 'platform');
        $platform_total = $platforms->sum();
        $platforms_percentages = $platform_total > 0
            ? $platforms->map(fn ($count) => $this->percentage($count, $platform_total))
            : collect();

        $devices_count = DB::table('devices')
            ->selectRaw('device, count(*) as count')
            ->groupBy('device')
            ->pluck('count', 'device');
        $device_total = $devices_count->sum();
        $devices_percentages = $device_total > 0
            ? $devices_count->map(fn ($count) => $this->percentage($count, $device_total))
            : collect();

        $clients = DB::table('devices')
            ->selectRaw('device_type, count(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type');
        $client_total = $clients->sum();
        $clients_percentages = $client_total > 0
            ? $clients->map(fn ($count) => $this->percentage($count, $client_total))
            : collect();

        $locationsNames = DB::table('geolocations')
            ->selectRaw('country_name, count(*) as count')
            ->groupBy('country_name')
            ->pluck('count', 'country_name');
        $locations_total = $locationsNames->sum();
        $locations_percentages = $locations_total > 0
            ? $locationsNames->map(fn ($count) => $this->percentage($count, $locations_total))
            : collect();

        $socialLinks = SocialLink::with('shortlink')->get();
        $linksCount = (int) DB::table('short_links')->sum('count');

        return view('backend.dashboard.dashboard', compact(
            'engagements',
            'locations',
            'devices',
            'socialLinks',
            'linksCount',
            'platforms',
            'platforms_percentages',
            'platform_total',
            'devices_count',
            'devices_percentages',
            'device_total',
            'clients',
            'clients_percentages',
            'client_total',
            'locationsNames',
            'locations_percentages',
            'locations_total',
            'totalReach',
            'totalCount',
            'totalDevices',
            'totalLocations'
        ));
    }

    /**
     * Stream QR code image for the current user's profile URL.
     */
    public function downloadQRCode(): StreamedResponse
    {
        $username = auth()->user()->username;
        $url = route('home') . '/' . $username;
        $imageName = $username . '-qrcode.png';

        $headers = [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $imageName . '"',
        ];

        return new StreamedResponse(function () use ($url) {
            echo QrCode::eye('circle')->size(200)->generate($url);
        }, 200, $headers);
    }
}
