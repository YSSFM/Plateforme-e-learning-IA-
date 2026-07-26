<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Niveau;

class UserController extends Controller
{
    /**
     * Liste des utilisateurs
     */
    public function index()
    {
        $users = User::with('niveau')->orderBy('created_at', 'desc')->paginate(20);
        $niveaux = Niveau::all();
        
        return view('admin.users.index', compact('users', 'niveaux'));
    }
    
    /**
     * Afficher un utilisateur spécifique
     */
    public function show($id)
    {
        $user = User::with(['niveau', 'soumissions.exercice'])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }
    
    /**
     * Bloquer un utilisateur
     */
    public function block($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher l'admin de se bloquer lui-même
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous bloquer vous-même.');
        }
        
        $user->update(['statut' => 'bloque']);
        
        return redirect()->back()->with('success', "L'utilisateur {$user->username} a été bloqué.");
    }
    
    /**
     * Débloquer un utilisateur
     */
    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $user->update(['statut' => 'actif']);
        
        return redirect()->back()->with('success', "L'utilisateur {$user->username} a été débloqué.");
    }
    
    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher l'admin de se supprimer lui-même
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }
        
        $username = $user->username;
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', "L'utilisateur {$username} a été supprimé.");
    }
    
    /**
     * Action en masse sur les utilisateurs
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
            'action' => 'required|in:block,unblock,delete'
        ]);
        
        $userIds = $request->users;
        
        // Empêcher l'admin de se modifier/supprimer lui-même
        if (in_array(auth()->id(), $userIds) && $request->action !== 'unblock') {
            return redirect()->back()->with('error', 'Vous ne pouvez pas effectuer cette action sur vous-même.');
        }
        
        switch ($request->action) {
            case 'block':
                User::whereIn('id', $userIds)->update(['statut' => 'bloque']);
                $message = 'Utilisateurs bloqués avec succès.';
                break;
            case 'unblock':
                User::whereIn('id', $userIds)->update(['statut' => 'actif']);
                $message = 'Utilisateurs débloqués avec succès.';
                break;
            case 'delete':
                User::whereIn('id', $userIds)->delete();
                $message = 'Utilisateurs supprimés avec succès.';
                break;
        }
        
        return redirect()->back()->with('success', $message);
    }
}