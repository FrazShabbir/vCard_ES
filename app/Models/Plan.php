<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'key', 'name', 'profile_type', 'months', 'price_paisa', 'badge', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'months' => 'integer',
        'price_paisa' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
