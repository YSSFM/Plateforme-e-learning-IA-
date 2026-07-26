<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Progression;
use App\Models\Cours;
use App\Models\Soumission;

class ProgressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Récupérer toutes les progressions de l'utilisateur
        $progressions = Progression::with('cours.module.niveau')
            ->where('user_id', $user->id)
            ->get();
        
        // Calcul automatique de la progression en fonction des activités
        // 1. Récupérer tous les cours du niveau de l'utilisateur
        $totalCourses = Cours::whereHas('module', function($query) use ($user) {
            $query->where('niveau_id', $user->niveau_id);
        })->count();
        
        // 2. Récupérer les cours terminés (ceux où l'utilisateur a soumis tous les exercices)
        $exercicesParCours = \App\Models\Exercice::select('cours_id')
            ->whereHas('cours.module', function($query) use ($user) {
                $query->where('niveau_id', $user->niveau_id);
            })
            ->get()
            ->groupBy('cours_id');
        
        $coursTermines = [];
        $coursEnCours = [];
        
        foreach ($exercicesParCours as $coursId => $exercices) {
            $exerciceIds = $exercices->pluck('id')->toArray();
            
            // Compter les exercices soumis pour ce cours
            $soumisCount = Soumission::where('user_id', $user->id)
                ->whereIn('exercice_id', $exerciceIds)
                ->count();
            
            if ($soumisCount > 0 && $soumisCount < count($exercices)) {
                $coursEnCours[] = $coursId;
            } elseif ($soumisCount >= count($exercices) && count($exercices) > 0) {
                $coursTermines[] = $coursId;
            }
        }
        
        // Mettre à jour ou créer les progressions automatiquement
        foreach ($coursTermines as $coursId) {
            Progression::updateOrCreate(
                ['user_id' => $user->id, 'cours_id' => $coursId],
                ['statut' => 'termine']
            );
        }
        
        foreach ($coursEnCours as $coursId) {
            Progression::updateOrCreate(
                ['user_id' => $user->id, 'cours_id' => $coursId],
                ['statut' => 'en_cours']
            );
        }
        
        // Recharger les progressions mises à jour
        $progressions = Progression::with('cours.module.niveau')
            ->where('user_id', $user->id)
            ->get();
        
        $completedCourses = $progressions->where('statut', 'termine')->count();
        $inProgressCourses = $progressions->where('statut', 'en_cours')->count();
        $notStartedCourses = $totalCourses - $completedCourses - $inProgressCourses;
        
        $percentage = $totalCourses > 0 ? round(($completedCourses / $totalCourses) * 100) : 0;
        
        return view('user.progress.index', compact(
            'progressions', 
            'totalCourses', 
            'completedCourses',
            'inProgressCourses',
            'notStartedCourses',
            'percentage'
        ));
    }
}