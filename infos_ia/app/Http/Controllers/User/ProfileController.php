<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Niveau;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $niveaux = Niveau::orderBy('ordre')->get();
        
        return view('user.profile.edit', compact('user', 'niveaux'));
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'username' => 'required|string|max:125|unique:users,username,' . $user->id,
            'email' => 'required|email|max:125|unique:users,email,' . $user->id,
        ]);
        
        $user->update($request->only('username', 'email'));
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }
    
    public function chooseLevel(Request $request)
    {
        $request->validate([
            'niveau_id' => 'required|exists:niveaux,id'
        ]);
        
        auth()->user()->update(['niveau_id' => $request->niveau_id]);
        
        return redirect()->route('user.dashboard')->with('success', 'Niveau enregistré avec succès !');
    }
}