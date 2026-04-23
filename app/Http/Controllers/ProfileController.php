<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de profil
     */
    public function edit()
    {
        $user     = Auth::user();
        $products = $user->products()->with('category')->latest()->get();
        $orders   = $user->orders()->latest()->take(5)->get();

        return view('profile.edit', compact('user', 'products', 'orders'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        // Changer le mot de passe si fourni
        if ($request->filled('password')) {
            $request->validate([
                'current_password' => 'required',
                'password'         => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
            }

            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profil mis à jour avec succès !');
    }
}
