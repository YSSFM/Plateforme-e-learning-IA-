<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Exercice;
use App\Models\Soumission;

class ExerciseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $exercices = Exercice::with('cours.module')
            ->whereHas('cours.module', function($query) use ($user) {
                $query->where('niveau_id', $user->niveau_id);
            })
            ->paginate(15);
        
        // Marquer ceux déjà soumis
        $submittedIds = Soumission::where('user_id', $user->id)
            ->pluck('exercice_id')
            ->toArray();
        
        return view('user.exercices.index', compact('exercices', 'submittedIds'));
    }
    
    public function show($id)
    {
        $exercice = Exercice::with('cours')->findOrFail($id);
        
        // Vérifier si déjà soumis
        $submission = Soumission::where('user_id', auth()->id())
            ->where('exercice_id', $id)
            ->first();
        
        $deadlinePassed = $exercice->isDeadlineDepassee();
        
        // "Dernier jour" = la deadline tombe le jour même
        $isLastDay = $exercice->deadline && Carbon::parse($exercice->deadline)->isToday();
        
        // Retrait possible tant que la deadline n'est pas dépassée
        $canWithdraw = $submission && !$deadlinePassed;
        
        return view('user.exercices.show', compact('exercice', 'submission', 'deadlinePassed', 'isLastDay', 'canWithdraw'));
    }
    
    public function submit(Request $request, $id)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:pdf,doc,docx,zip|max:5120'
        ]);
        
        $exercice = Exercice::findOrFail($id);

        if ($exercice->isDeadlineDepassee()) {
            return redirect()->route('user.exercises.show', $id)->with('error', 'La date limite est dépassée, vous ne pouvez plus soumettre.');
        }
        
        // Upload du fichier
        $file = $request->file('fichier');
        $filename = time() . '_' . auth()->id() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('submissions', $filename, 'public');
        
        // Créer la soumission
        Soumission::create([
            'user_id' => auth()->id(),
            'exercice_id' => $id,
            'fichier' => $filename,
            'tentative' => Soumission::where('user_id', auth()->id())->where('exercice_id', $id)->count() + 1,
            'statut' => 'soumis'
        ]);
        
        return redirect()->route('user.exercises.show', $id)->with('success', 'Exercice soumis avec succès !');
    }

    /**
     * Retirer sa soumission (uniquement si la date limite n'est pas dépassée)
     */
    public function withdraw($submissionId)
    {
        $submission = Soumission::where('user_id', auth()->id())->findOrFail($submissionId);
        $exercice = Exercice::findOrFail($submission->exercice_id);

        if ($exercice->isDeadlineDepassee()) {
            return redirect()->back()->with('error', 'La date limite est dépassée, vous ne pouvez plus retirer votre soumission.');
        }

        if ($submission->fichier && Storage::disk('public')->exists('submissions/' . $submission->fichier)) {
            Storage::disk('public')->delete('submissions/' . $submission->fichier);
        }

        $submission->delete();

        return redirect()->route('user.exercises.show', $exercice->id)->with('success', 'Votre soumission a été retirée. Vous pouvez la soumettre à nouveau avant la date limite.');
    }
}