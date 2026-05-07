<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles  = Vehicle::where('available', true)->get();
        $categories = $vehicles->pluck('category')->unique()->values();
        return view('front.vehicles', compact('vehicles', 'categories'));
    }

    public function show(Vehicle $vehicle)
    {
        if (!$vehicle->available) {
            abort(404);
        }

        // Occupied periods for this vehicle (active reservations)
        $occupied = $vehicle->reservations()
            ->whereNotIn('status', ['rejected', 'completed'])
            ->orderBy('start_date')
            ->get(['start_date', 'end_date', 'status']);

        return view('front.vehicle-detail', compact('vehicle', 'occupied'));
    }

    public function storeComment(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body'   => 'required|string|max:500',
        ]);

        \App\Models\Comment::updateOrCreate(
            ['vehicle_id' => $vehicle->id, 'user_id' => auth()->id()],
            ['rating' => $request->rating, 'body' => strip_tags(trim($request->body))]
        );

        return back()->with('success', 'Votre avis a été publié avec succès.');
    }
}
