<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Soumission;

class SubmissionController extends Controller
{
    /**
     * Liste des soumissions
     */
    public function index()
    {
        $submissions = Soumission::with(['user', 'exercice.cours.module'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.submissions.index', compact('submissions'));
    }
    
    /**
     * Afficher une soumission pour correction
     */
    public function show($id)
    {
        $submission = Soumission::with(['user', 'exercice.cours'])->findOrFail($id);
        
        return view('admin.submissions.grade', compact('submission'));
    }
    
    /**
     * Enregistrer la note et le feedback
     */
    public function grade(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|numeric|min:0|max:20',
            'feedback' => 'nullable|string'
        ]);
        
        $submission = Soumission::findOrFail($id);
        $submission->update([
            'note' => $request->note,
            'feedback' => $request->feedback,
            'statut' => 'corrige'
        ]);
        
        return redirect()->route('admin.submissions.index')->with('success', 'Soumission corrigée avec succès.');
    }

    /**
     * Supprimer une soumission.
     * Sans impact négatif côté étudiant : celui-ci retrouvera simplement
     * l'exercice comme "non soumis" et pourra le soumettre à nouveau
     * (si la date limite n'est pas dépassée).
     */
    public function destroy($id)
    {
        $submission = Soumission::findOrFail($id);

        if ($submission->fichier && Storage::disk('public')->exists('submissions/' . $submission->fichier)) {
            Storage::disk('public')->delete('submissions/' . $submission->fichier);
        }

        $submission->delete();

        return redirect()->route('admin.submissions.index')->with('success', 'Soumission supprimée avec succès.');
    }
}