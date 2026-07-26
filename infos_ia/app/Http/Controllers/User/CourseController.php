<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Progression;

class CourseController extends Controller
{
    public function show($id)
    {
        $course = Cours::with(['module', 'ressources', 'exercices'])
            ->where('statut', 'publie')
            ->findOrFail($id);
        
        // Mettre à jour automatiquement la progression
        $progression = Progression::updateProgress(auth()->id(), $course->id, 'view');
        
        // Vérifier si le cours est terminé (tous les exercices soumis)
        $isCompleted = Progression::checkCourseCompletion(auth()->id(), $course->id);
        
        if ($isCompleted && $progression->statut !== 'termine') {
            $progression->statut = 'termine';
            $progression->save();
        }
        
        return view('user.courses.show', compact('course', 'progression'));
    }

    /**
     * Mise à jour manuelle de la progression (bouton dans la vue du cours)
     */
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:non_commence,en_cours,termine',
        ]);

        $course = Cours::where('statut', 'publie')->findOrFail($id);

        $action = $request->statut === 'termine' ? 'complete' : 'view';
        $progression = Progression::updateProgress(auth()->id(), $course->id, $action);

        // Si l'utilisateur force un statut précis différent de ce que déduit updateProgress()
        if ($progression->statut !== $request->statut) {
            $progression->statut = $request->statut;
            $progression->save();
        }

        return response()->json(['success' => true, 'statut' => $progression->statut]);
    }
}