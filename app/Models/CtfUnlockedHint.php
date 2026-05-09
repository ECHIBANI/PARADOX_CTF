<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtfUnlockedHint extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'hint_number',
        'penalty',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(CtfChallenge::class);
    }
}
