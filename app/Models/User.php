<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'phone', 'password', 'role', 'blocked'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['blocked' => 'boolean'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Vehicle::class, 'favorites', 'user_id', 'vehicle_id')->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBlocked(): bool
    {
        return (bool) $this->blocked;
    }

    // ── CTF Relations ──────────────────────────────────────────────────────────

    /** Points CTF gagnés par cet utilisateur. */
    public function ctfScores()
    {
        return $this->hasMany(\App\Models\CtfScore::class);
    }

    /** Toutes les soumissions de flag de cet utilisateur. */
    public function ctfSubmissions()
    {
        return $this->hasMany(\App\Models\CtfSubmission::class);
    }

    /** Challenges CTF résolus par cet utilisateur (via ctf_scores). */
    public function solvedCtfChallenges()
    {
        return $this->belongsToMany(
            \App\Models\CtfChallenge::class,
            'ctf_scores',
            'user_id',
            'challenge_id'
        )->withTimestamps();
    }

    /** Total des points CTF de cet utilisateur. */
    public function totalCtfPoints(): int
    {
        $earned = $this->ctfScores()->sum('total_points');
        $penalties = $this->ctfUnlockedHints()->sum('penalty');
        return max(0, $earned - $penalties);
    }

    /** Indices débloqués par l'utilisateur. */
    public function ctfUnlockedHints()
    {
        return $this->hasMany(\App\Models\CtfUnlockedHint::class);
    }
}
