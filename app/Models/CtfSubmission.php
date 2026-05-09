<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle CtfSubmission
 * Enregistre chaque tentative de soumission d'un flag.
 */
class CtfSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'submitted_flag',
        'is_correct',
        'ip_address',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Relation : Utilisateur ayant soumis ce flag.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Challenge associé à cette soumission.
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(CtfChallenge::class, 'challenge_id');
    }
}
