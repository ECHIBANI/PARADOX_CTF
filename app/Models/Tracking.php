<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $fillable = ['vehicle_id', 'latitude', 'longitude'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
