<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Exercice;
use App\Models\Cours;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercices = Exercice::with('cours.module.niveau')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.exercices.index', compact('exercices'));
    }

    public function create()
    {
        $cours = Cours::with('module.niveau')->where('statut', 'publie')->orderBy('titre')->get();
        return view('admin.exercices.create', compact('cours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'titre' => 'required|string|max:100',
            'enonce' => 'required_without:fichier_enonce|nullable|string',
            'fichier_enonce' => 'required_without:enonce|nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'type' => 'required|in:qcm,theorique,pratique',
            'points_max' => 'nullable|integer|min:1|max:20',
            'deadline' => 'nullable|date',
            'correction' => 'nullable|string',
            'fichier_correction' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $data = $request->except(['fichier_enonce', 'fichier_correction']);

        if ($request->hasFile('fichier_enonce')) {
            $file = $request->file('fichier_enonce');
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('exercices', $filename, 'public');
            $data['fichier_enonce'] = $filename;
        }

        if ($request->hasFile('fichier_correction')) {
            $file = $request->file('fichier_correction');
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('exercices/corrections', $filename, 'public');
            $data['fichier_correction'] = $filename;
        }

        Exercice::create($data);

        return redirect()->route('admin.exercices.index')->with('success', 'Exercice créé avec succès.');
    }

    public function show($id)
    {
        $exercice = Exercice::with('cours')->findOrFail($id);
        return view('admin.exercices.show', compact('exercice'));
    }

    public function edit($id)
    {
        $exercice = Exercice::findOrFail($id);
        $cours = Cours::with('module.niveau')->where('statut', 'publie')->orderBy('titre')->get();
        return view('admin.exercices.edit', compact('exercice', 'cours'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'titre' => 'required|string|max:100',
            'enonce' => 'required_without:fichier_enonce|nullable|string',
            'fichier_enonce' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'type' => 'required|in:qcm,theorique,pratique',
            'points_max' => 'nullable|integer|min:1|max:20',
            'deadline' => 'nullable|date',
            'correction' => 'nullable|string',
            'fichier_correction' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $exercice = Exercice::findOrFail($id);
        $data = $request->except(['fichier_enonce', 'fichier_correction']);

        if ($request->hasFile('fichier_enonce')) {
            if ($exercice->fichier_enonce && Storage::disk('public')->exists('exercices/' . $exercice->fichier_enonce)) {
                Storage::disk('public')->delete('exercices/' . $exercice->fichier_enonce);
            }
            $file = $request->file('fichier_enonce');
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('exercices', $filename, 'public');
            $data['fichier_enonce'] = $filename;
        }

        if ($request->hasFile('fichier_correction')) {
            if ($exercice->fichier_correction && Storage::disk('public')->exists('exercices/corrections/' . $exercice->fichier_correction)) {
                Storage::disk('public')->delete('exercices/corrections/' . $exercice->fichier_correction);
            }
            $file = $request->file('fichier_correction');
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->storeAs('exercices/corrections', $filename, 'public');
            $data['fichier_correction'] = $filename;
        }

        $exercice->update($data);

        return redirect()->route('admin.exercices.index')->with('success', 'Exercice modifié avec succès.');
    }

    public function destroy($id)
    {
        $exercice = Exercice::withCount('soumissions')->findOrFail($id);

        if ($exercice->soumissions_count > 0) {
            return redirect()->route('admin.exercices.index')
                ->with('error', "Impossible de supprimer « {$exercice->titre} » : {$exercice->soumissions_count} étudiant(s) l'ont déjà soumis. Supprimez d'abord ces soumissions si nécessaire.");
        }

        if ($exercice->fichier_enonce && Storage::disk('public')->exists('exercices/' . $exercice->fichier_enonce)) {
            Storage::disk('public')->delete('exercices/' . $exercice->fichier_enonce);
        }
        if ($exercice->fichier_correction && Storage::disk('public')->exists('exercices/corrections/' . $exercice->fichier_correction)) {
            Storage::disk('public')->delete('exercices/corrections/' . $exercice->fichier_correction);
        }

        $exercice->delete();

        return redirect()->route('admin.exercices.index')->with('success', 'Exercice supprimé avec succès.');
    }
}