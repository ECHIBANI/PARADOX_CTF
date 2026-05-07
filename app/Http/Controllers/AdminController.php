<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ─── DASHBOARD ─────────────────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total'     => Reservation::count(),
            'pending'   => Reservation::where('status', 'pending')->count(),
            'confirmed' => Reservation::where('status', 'confirmed')->count(),
            'revenue'   => Reservation::where('status', 'confirmed')->sum('total_price'),
            'vehicles'  => Vehicle::count(),
            'users'     => User::where('role', 'user')->count(),
        ];

        $pending = Reservation::with(['user', 'vehicle'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pending'));
    }

    // ─── RESERVATIONS ───────────────────────────────────────────────────────
    public function reservations(Request $request)
    {
        $query = Reservation::with(['user', 'vehicle'])->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%"))
                ->orWhere('reservation_number', 'like', "%$s%");
        }

        $reservations = $query->paginate(20);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status'     => 'required|in:confirmed,rejected,completed',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $reservation->update([
            'status'     => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        $label = match($request->status) {
            'confirmed' => 'confirmée',
            'rejected'  => 'refusée',
            'completed' => 'marquée terminée',
        };

        return back()->with('success', "Réservation {$reservation->reservation_number} {$label}.");
    }

    public function voucherAdmin(Reservation $reservation)
    {
        $reservation->load(['user', 'vehicle']);
        return view('front.voucher', compact('reservation'));
    }

    // ─── VEHICLES ───────────────────────────────────────────────────────────
    public function vehicles()
    {
        $vehicles = Vehicle::orderByDesc('created_at')->get();
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function createVehicle()
    {
        return view('admin.vehicles.form', ['vehicle' => new Vehicle()]);
    }

    public function storeVehicle(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'category'      => 'required|string|max:50',
            'price_per_day' => 'required|integer|min:50|max:99999',
            'seats'         => 'required|integer|min:2|max:15',
            'transmission'  => 'required|in:Manuelle,Automatique',
            'ac'            => 'boolean',
            'available'     => 'boolean',
            'description'   => 'nullable|string|max:1000',
            'image_url'     => 'nullable|url',
            'image_file'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data['ac']        = $request->boolean('ac', true);
        $data['available'] = $request->boolean('available', true);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('vehicles', 'public');
            $data['image'] = $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        unset($data['image_url'], $data['image_file']);
        Vehicle::create($data);

        return redirect()->route('admin.vehicles')->with('success', 'Véhicule ajouté avec succès.');
    }

    public function editVehicle(Vehicle $vehicle)
    {
        return view('admin.vehicles.form', compact('vehicle'));
    }

    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'category'      => 'required|string|max:50',
            'price_per_day' => 'required|integer|min:50|max:99999',
            'seats'         => 'required|integer|min:2|max:15',
            'transmission'  => 'required|in:Manuelle,Automatique',
            'ac'            => 'boolean',
            'available'     => 'boolean',
            'description'   => 'nullable|string|max:1000',
            'image_url'     => 'nullable|url',
            'image_file'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data['ac']        = $request->boolean('ac');
        $data['available'] = $request->boolean('available');

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('vehicles', 'public');
            $data['image'] = $path;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        unset($data['image_url'], $data['image_file']);
        $vehicle->update($data);

        return redirect()->route('admin.vehicles')->with('success', 'Véhicule modifié avec succès.');
    }

    public function deleteVehicle(Vehicle $vehicle)
    {
        $vehicle->delete();
        return back()->with('success', 'Véhicule supprimé.');
    }

    // ─── USERS ──────────────────────────────────────────────────────────────
    public function users()
    {
        $users = User::where('role', 'user')
            ->withCount('reservations')
            ->orderByDesc('created_at')
            ->get();
        return view('admin.users.index', compact('users'));
    }

    public function toggleBlock(User $user)
    {
        if ($user->isAdmin()) abort(403);
        $user->update(['blocked' => !$user->blocked]);
        $action = $user->blocked ? 'bloqué' : 'débloqué';
        return back()->with('success', "Utilisateur {$user->name} {$action}.");
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin()) abort(403, 'Impossible de supprimer un administrateur.');
        $user->delete();
        return back()->with('success', "Utilisateur {$user->name} supprimé définitivement.");
    }
}
