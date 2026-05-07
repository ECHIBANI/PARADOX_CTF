<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'user_id', 'rating', 'body'];

    protected $casts = ['rating' => 'integer'];

    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
