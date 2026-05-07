<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_number','user_id','vehicle_id',
        'start_date','end_date','total_price','acompte','status','admin_note'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }

    public function getDaysAttribute(): int
    {
        return max(1, $this->start_date->diffInDays($this->end_date));
    }

    public function getResteAttribute(): int
    {
        return $this->total_price - $this->acompte;
    }

    public static function generateNumber(): string
    {
        return 'RES-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'En attente',
            'confirmed' => 'Confirmée',
            'rejected'  => 'Refusée',
            'completed' => 'Terminée',
            default     => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'   => 'warning',
            'confirmed' => 'success',
            'rejected'  => 'danger',
            'completed' => 'info',
            default     => 'secondary',
        };
    }
}
