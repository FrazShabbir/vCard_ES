<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'subscription_id', 'amount_paisa', 'method', 'purpose',
        'status', 'reference', 'notes', 'confirmed_by', 'confirmed_at',
    ];

    protected $casts = [
        'amount_paisa' => 'integer',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
