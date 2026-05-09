<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle CtfScore
 * Enregistre les points gagnés par un utilisateur pour un challenge résolu.
 * Contrainte unique : user_id + challenge_id (un utilisateur ne gagne les points qu'une fois).
 */
class CtfScore extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'points',
        'bonus_percent',
        'bonus_points',
    ];

    protected $casts = [
        'bonus_percent' => 'integer',
        'bonus_points'  => 'integer',
        'total_points'  => 'integer',
    ];


    /**
     * Relation : Utilisateur ayant gagné ces points.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Challenge associé à ce score.
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(CtfChallenge::class, 'challenge_id');
    }
}
