<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(Request $request, Vehicle $vehicle)
    {
        // 1. Validate inputs
        $data = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ], [
            'start_date.required'        => 'La date de début est obligatoire.',
            'start_date.after_or_equal'  => 'La date de début ne peut pas être dans le passé.',
            'end_date.required'          => 'La date de fin est obligatoire.',
            'end_date.after'             => 'La date de fin doit être après la date de début.',
        ]);

        // 2. Check user not blocked
        if (Auth::user()->isBlocked()) {
            return back()->withErrors(['general' => 'Votre compte est bloqué.']);
        }

        // 3. Anti-overlap check (backend - STRICT)
        if (!$vehicle->isAvailableFor($data['start_date'], $data['end_date'])) {
            return back()
                ->withErrors(['general' => '❌ Ce véhicule est déjà réservé pour ces dates. Veuillez choisir d\'autres dates.'])
                ->withInput();
        }

        // 4. Calculate price
        $start = \Carbon\Carbon::parse($data['start_date']);
        $end   = \Carbon\Carbon::parse($data['end_date']);
        $days  = max(1, $start->diffInDays($end));
        $total   = $days * $vehicle->price_per_day;
        $acompte = (int) round($total * 0.3);

        // 5. Create reservation
        $reservation = Reservation::create([
            'reservation_number' => Reservation::generateNumber(),
            'user_id'            => Auth::id(),
            'vehicle_id'         => $vehicle->id,
            'start_date'         => $data['start_date'],
            'end_date'           => $data['end_date'],
            'total_price'        => $total,
            'acompte'            => $acompte,
            'status'             => 'pending',
        ]);

        return redirect()
            ->route('reservations.voucher', $reservation)
            ->with('success', 'Réservation envoyée avec succès ! En attente de confirmation.');
    }

    public function voucher(Reservation $reservation)
    {
        // Security: only owner or admin
        if (!Auth::user()->isAdmin() && $reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->load(['user', 'vehicle']);
        return view('front.voucher', compact('reservation'));
    }

    public function myReservations()
    {
        $reservations = Reservation::with('vehicle')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('client.my-reservations', compact('reservations'));
    }
}
