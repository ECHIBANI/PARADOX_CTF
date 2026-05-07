<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites()->get();
        return view('client.favorites', compact('favorites'));
    }

    public function toggle(Vehicle $vehicle)
    {
        $user = auth()->user();
        
        $hasFavorited = $user->favorites()->where('vehicle_id', $vehicle->id)->exists();
        
        if ($hasFavorited) {
            $user->favorites()->detach($vehicle->id);
            return response()->json(['status' => 'removed', 'message' => 'Véhicule retiré des favoris.']);
        } else {
            $user->favorites()->attach($vehicle->id);
            return response()->json(['status' => 'added', 'message' => 'Véhicule ajouté aux favoris.']);
        }
    }
}
