<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'name','category','price_per_day','seats',
        'transmission','ac','image','available','description'
    ];

    protected $casts = ['ac' => 'boolean', 'available' => 'boolean'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'vehicle_id', 'user_id')->withTimestamps();
    }

    /**
     * Anti-overlap check (strict):
     * Returns true if the vehicle is available for [startDate, endDate).
     * Blocks if any active reservation satisfies:
     *   new_start < existing_end  AND  new_end > existing_start
     */
    public function isAvailableFor(string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        $query = $this->reservations()
            ->whereNotIn('status', ['rejected', 'completed'])
            ->where('start_date', '<', $endDate)
            ->where('end_date',   '>', $startDate);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->doesntExist();
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return 'https://via.placeholder.com/600x400?text=No+Image';
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('uploads/vehicles/' . $this->image);
    }
}
