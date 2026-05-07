<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

// ── PUBLIC ──────────────────────────────────────────────────────────────────
Route::middleware([\App\Http\Middleware\NoAdmin::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/vehicules', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicules/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
});

// ── AUTH ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/connexion',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion',   [AuthController::class, 'login']);
    Route::get('/inscription',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);
});

Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── CLIENT (authenticated) ───────────────────────────────────────────────────
Route::middleware(['auth', 'not.blocked'])->group(function () {
    Route::post('/vehicules/{vehicle}/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/vehicules/{vehicle}/comment', [VehicleController::class, 'storeComment'])->name('vehicles.comment');
    Route::get('/reservation/{reservation}/bon', [ReservationController::class, 'voucher'])->name('reservations.voucher');
    Route::get('/mes-reservations', [ReservationController::class, 'myReservations'])->name('client.reservations');
    
    Route::get('/mes-favoris', [FavoriteController::class, 'index'])->name('client.favorites');
    Route::post('/favoris/{vehicle}/toggle', [FavoriteController::class, 'toggle'])->name('client.favorites.toggle');
    
    Route::get('/mon-compte', [ProfileController::class, 'edit'])->name('client.profile.edit');
    Route::put('/mon-compte', [ProfileController::class, 'update'])->name('client.profile.update');
});

// ── ADMIN ────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                                             [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservations',                                 [AdminController::class, 'reservations'])->name('reservations');
    Route::patch('/reservations/{reservation}/status',          [AdminController::class, 'updateStatus'])->name('reservations.status');
    Route::get('/reservations/{reservation}/bon',               [AdminController::class, 'voucherAdmin'])->name('voucher');
    Route::get('/vehicules',                                    [AdminController::class, 'vehicles'])->name('vehicles');
    Route::get('/vehicules/ajouter',                            [AdminController::class, 'createVehicle'])->name('vehicles.create');
    Route::post('/vehicules',                                   [AdminController::class, 'storeVehicle'])->name('vehicles.store');
    Route::get('/vehicules/{vehicle}/modifier',                 [AdminController::class, 'editVehicle'])->name('vehicles.edit');
    Route::put('/vehicules/{vehicle}',                          [AdminController::class, 'updateVehicle'])->name('vehicles.update');
    Route::delete('/vehicules/{vehicle}',                       [AdminController::class, 'deleteVehicle'])->name('vehicles.delete');
    Route::get('/utilisateurs',                                 [AdminController::class, 'users'])->name('users');
    Route::patch('/utilisateurs/{user}/bloquer',                [AdminController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::delete('/utilisateurs/{user}',                       [AdminController::class, 'deleteUser'])->name('users.delete');
});

// ── API (Dashboard AJAX) ───────────────────────────────────────────────────
Route::prefix('api')->group(function () {
    Route::get('/stats', [\App\Http\Controllers\ApiController::class, 'stats']);
    Route::get('/cars', [\App\Http\Controllers\ApiController::class, 'cars']);
    Route::get('/tracking', [\App\Http\Controllers\ApiController::class, 'tracking']);
});
