<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Geolocation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function devices()
    {
        return $this->hasMany(Device::class,'geolocation_id');
    }
}
