<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\Tracking;
use Carbon\Carbon;

class ApiController extends Controller
{
    // 1. Stats (Tickets sold/Revenue with filter)
    public function stats(Request $request)
    {
        $filter = $request->get('filter', 'month'); // 'today', 'week', 'month'
        
        $query = Reservation::whereIn('status', ['confirmed', 'completed']);
        
        if ($filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'week') {
            $query->where('created_at', '>=', Carbon::now()->startOfWeek());
        } else {
            // default month
            $query->where('created_at', '>=', Carbon::now()->startOfMonth());
        }

        $ticketsSold = $query->count();
        $revenue = $query->sum('total_price');

        // Prepare data for the Chart (e.g. daily breakdown for the current filter)
        // For simplicity, we return the aggregate plus a dummy series or actual group by dates.
        $chartData = $query->selectRaw('DATE(created_at) as date, count(*) as tickets, sum(total_price) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $chartData->pluck('date');
        $tickets = $chartData->pluck('tickets');
        $revenues = $chartData->pluck('revenue');

        return response()->json([
            'tickets_sold' => $ticketsSold,
            'revenue' => $revenue,
            'chart' => [
                'labels' => $labels,
                'tickets' => $tickets,
                'revenues' => $revenues,
            ]
        ]);
    }

    // 2. Most Preferred Cars
    public function cars()
    {
        // Top 3 cars by bookings
        $cars = Vehicle::withCount(['reservations' => function ($query) {
            $query->whereIn('status', ['confirmed', 'completed']);
        }])
        ->orderByDesc('reservations_count')
        ->take(3)
        ->get();

        return response()->json($cars);
    }

    // 3. Map Tracking Data
    public function tracking()
    {
        $trackings = Tracking::with('vehicle')->get()->map(function ($tracking) {
            // Determine if active: has a reservation today
            $isActive = $tracking->vehicle->reservations()
                ->where('start_date', '<=', Carbon::today())
                ->where('end_date', '>=', Carbon::today())
                ->where('status', 'confirmed')
                ->exists();

            return [
                'id' => $tracking->id,
                'car_id' => $tracking->vehicle_id,
                'car_name' => $tracking->vehicle->name,
                'car_model' => $tracking->vehicle->category,
                'latitude' => $tracking->latitude,
                'longitude' => $tracking->longitude,
                'status' => $isActive ? 'Busy' : 'Available',
                'timestamp' => $tracking->updated_at,
            ];
        });

        return response()->json($trackings);
    }
}
