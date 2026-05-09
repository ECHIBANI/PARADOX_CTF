<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle CtfChallenge
 * Représente un challenge CTF dans le module PARDOX CTF.
 */
class CtfChallenge extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'statement',
        'category',
        'difficulty',
        'points',
        'theme',
        'flag_hash',
        'image',
        'file_1',
        'file_2',
        'author',
        'attempts',
        'hint_1_title',
        'hint_1_content',
        'hint_2_title',
        'hint_2_content',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /**
     * Relation : Soumissions liées à ce challenge.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(CtfSubmission::class, 'challenge_id');
    }

    /**
     * Relation : Scores liés à ce challenge.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(CtfScore::class, 'challenge_id');
    }

    /**
     * Retourne les utilisateurs qui ont résolu ce challenge (scores corrects).
     */
    public function solvers(): HasMany
    {
        return $this->hasMany(CtfScore::class, 'challenge_id')->with('user')->latest();
    }

    /**
     * Accesseur : retourne le label traduit de la difficulté.
     */
    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty) {
            'easy'   => 'Facile',
            'medium' => 'Moyen',
            'hard'   => 'Difficile',
            default  => ucfirst($this->difficulty),
        };
    }

    /**
     * Accesseur : retourne la classe CSS Bootstrap pour la difficulté.
     */
    public function getDifficultyClassAttribute(): string
    {
        return match($this->difficulty) {
            'easy'   => 'ctf-badge-easy',
            'medium' => 'ctf-badge-medium',
            'hard'   => 'ctf-badge-hard',
            default  => 'ctf-badge-easy',
        };
    }
}
