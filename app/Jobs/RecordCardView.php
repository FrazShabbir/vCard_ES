<?php

namespace App\Jobs;

use App\Models\Device;
use App\Models\Engagement;
use App\Models\Geolocation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stevebauman\Location\Facades\Location;

class RecordCardView implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public string $ip;
    public array $agent;

    public function __construct(int $userId, string $ip, array $agent)
    {
        $this->userId = $userId;
        $this->ip = $ip;
        $this->agent = $agent;
    }

    public function handle()
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        $user->increment('count');

        $locationInfo = config('app.env') === 'local'
            ? Location::get('182.185.236.113')
            : Location::get($this->ip);

        if (!$locationInfo) {
            return;
        }

        $findIP = Geolocation::where('user_id', $user->id)->where('ip_address', $this->ip)->first();
        $isUniqueLocation = false;
        if ($findIP) {
            $locationId = $findIP->id;
        } else {
            $location = Geolocation::create([
                'user_id' => $user->id,
                'ip_address' => $this->ip,
                'country_name' => $locationInfo->countryName,
                'country_code' => $locationInfo->countryCode,
                'region_code' => $locationInfo->regionCode,
                'region_name' => $locationInfo->regionName,
                'city_name' => $locationInfo->cityName,
                'zip_code' => $locationInfo->zipCode,
                'iso_code' => $locationInfo->isoCode,
                'postal_code' => $locationInfo->postalCode,
                'latitude' => $locationInfo->latitude,
                'longitude' => $locationInfo->longitude,
                'metro_code' => $locationInfo->metroCode,
                'area_code' => $locationInfo->areaCode,
                'timezone' => $locationInfo->timezone,
                'device' => $this->agent['device'] ?? null,
                'platform' => $this->agent['platform'] ?? null,
                'platform_version' => $this->agent['platform_version'] ?? null,
                'browser' => $this->agent['browser'] ?? null,
                'browser_version' => $this->agent['browser_version'] ?? null,
            ]);
            $locationId = $location->id;
            $isUniqueLocation = true;
        }

        $findDevice = Device::where('user_id', $user->id)
            ->where('ip_address', $this->ip)
            ->where('geolocation_id', $locationId)
            ->where('device', $this->agent['device'] ?? null)
            ->where('platform', $this->agent['platform'] ?? null)
            ->first();

        $isUniqueDevice = false;
        if (!$findDevice) {
            $device = Device::create([
                'user_id' => $user->id,
                'geolocation_id' => $locationId,
                'ip_address' => $this->ip,
                'device' => $this->agent['device'] ?? null,
                'device_type' => $this->agent['device_type'] ?? 'Other',
                'platform' => $this->agent['platform'] ?? null,
                'platform_version' => $this->agent['platform_version'] ?? null,
                'browser' => $this->agent['browser'] ?? null,
                'browser_version' => $this->agent['browser_version'] ?? null,
            ]);
            $user->increment('reach');
            $isUniqueDevice = true;
        } else {
            $device = $findDevice;
        }

        Engagement::create([
            'user_id' => $user->id,
            'geolocation_id' => $locationId,
            'device_id' => $device->id,
            'is_unique_location' => $isUniqueLocation,
            'is_unique_device' => $isUniqueDevice,
        ]);
    }
}
