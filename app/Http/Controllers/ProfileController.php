<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('client.profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:100|min:3',
            'phone' => [
                'required',
                'string',
                Rule::unique('users')->ignore($user->id),
                'regex:/^\+212[5-7][0-9]{8}$/'
            ],
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'     => 'Le nom complet est obligatoire.',
            'name.min'          => 'Le nom doit contenir au moins 3 caractères.',
            'phone.required'    => 'Le numéro de téléphone est obligatoire.',
            'phone.unique'      => 'Ce numéro est déjà utilisé.',
            'phone.regex'       => 'Format invalide. Utilisez +212XXXXXXXXX (ex: +212612345678).',
            'password.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'=> 'Les mots de passe ne correspondent pas.',
        ]);

        $user->name = strip_tags(trim($request->name));
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('client.profile.edit')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
