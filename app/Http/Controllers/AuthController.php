<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ], [
            'phone.required'    => 'Le numéro de téléphone est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['phone' => 'Numéro ou mot de passe incorrect.'])->withInput(['phone' => $request->phone]);
        }

        if ($user->isBlocked()) {
            return back()->withErrors(['phone' => 'Votre compte est bloqué. Contactez l\'administrateur.']);
        }

        if ($user->isAdmin()) {
            return back()->withErrors(['phone' => 'Espace client uniquement. Les administrateurs doivent se connecter sur /admin/login.'])->withInput(['phone' => $request->phone]);
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended(route('home'));
    }

    public function showAdminLogin()
    {
        return view('admin.auth.login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ], [
            'phone.required'    => 'Username requis.',
            'password.required' => 'Mot de passe requis.',
        ]);

        $user = User::where('phone', $request->phone)->orWhere('email', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['phone' => 'Identifiants incorrects.'])->withInput(['phone' => $request->phone]);
        }

        if (!$user->isAdmin()) {
            return back()->withErrors(['phone' => 'Accès refusé.'])->withInput(['phone' => $request->phone]);
        }

        Auth::login($user, true);

        return redirect()->route('admin.dashboard');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100|min:3',
            'phone'    => [
                'required', 'string', 'unique:users,phone',
                'regex:/^\+212[5-7][0-9]{8}$/'
            ],
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'     => 'Le nom complet est obligatoire.',
            'name.min'          => 'Le nom doit contenir au moins 3 caractères.',
            'phone.required'    => 'Le numéro de téléphone est obligatoire.',
            'phone.unique'      => 'Ce numéro est déjà utilisé.',
            'phone.regex'       => 'Format invalide. Utilisez +212XXXXXXXXX (ex: +212612345678).',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'=> 'Les mots de passe ne correspondent pas.',
        ]);

        $user = User::create([
            'name'     => strip_tags(trim($request->name)),
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Bienvenue ' . $user->name . ' !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
