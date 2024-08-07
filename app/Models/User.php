<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use SoftDeletes;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'status',
        'email',
        'phone',
        'password',
        'expiry',
        'country_name',
        'region_name',
        'city_name',
        'zip_code',
        'device',
        'platform',
        'platform_version',
        'browser',
        'browser_version',
        'terms',
        'type',
        'referral_code',
        'refer_by_id',
        'referral_code_entered'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function locations()
    {
        return $this->hasMany(Geolocation::class);
    }
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }
    public function vcard()
    {
        return $this->hasMany(Card::class)->where('status', 'Approved');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function uniquedevices()
    {
        return $this->devices()->where('is_unique', true)->count();
    }
    public function uniquegeolocations()
    {
        return $this->locations()->where('is_unique', true)->count();
    }

    public function engagements()
    {
        return $this->hasMany(Engagement::class);
    }
}
