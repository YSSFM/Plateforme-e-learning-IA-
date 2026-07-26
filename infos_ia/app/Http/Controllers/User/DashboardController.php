<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Exercice;
use App\Models\Soumission;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord utilisateur
     */
    public function index()
    {
        $user = auth()->user();
        
        // Modules disponibles selon le niveau de l'utilisateur
        $modules = collect();
        if ($user->niveau_id) {
            $modules = Module::with('niveau')
                ->where('niveau_id', $user->niveau_id)
                ->orderBy('ordre')
                ->get();
        }
        
        // Exercices à faire (non soumis)
        $submittedExerciceIds = Soumission::where('user_id', $user->id)
            ->pluck('exercice_id')
            ->toArray();
        
        $pendingExercices = Exercice::whereNotIn('id', $submittedExerciceIds)
            ->take(5)
            ->get();
        
        // Dernières soumissions
        $recentSubmissions = Soumission::with('exercice')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('user.dashboard', compact('modules', 'pendingExercices', 'recentSubmissions'));
    }
}