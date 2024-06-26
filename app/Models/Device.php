<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    public function location()
    {
        return $this->belongsTo(Geolocation::class,'geolocation_id');
    }
}
