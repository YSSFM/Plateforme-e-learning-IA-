<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Cours;
use App\Models\Module;

class CourseController extends Controller
{
    /**
     * Liste des cours
     */
    public function index()
    {
        $courses = Cours::with('module.niveau')->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.courses.index', compact('courses'));
    }
    
    /**
     * Formulaire d'ajout
     */
    public function create()
    {
        $modules = Module::with('niveau')->orderBy('ordre')->get();
        
        return view('admin.courses.create', compact('modules'));
    }
    
    /**
     * Enregistrer un nouveau cours
     */
    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'titre' => 'required|string|max:100',
            'contenu' => 'required|string',
            'ordre' => 'integer|nullable',
            'statut' => 'required|in:brouillon,publie,archive',
            'fichiers.*' => 'nullable|file|mimes:pdf,doc,docx,zip,ppt,pptx|max:10240'
        ]);
        
        $data = $request->except('fichiers');
        $uploadedFiles = [];
        
        // Vérifier si des fichiers ont été uploadés
        if ($request->hasFile('fichiers')) {
            $files = $request->file('fichiers');
            
            // Si un seul fichier est uploadé, le normaliser en tableau
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
                    $file->storeAs('courses', $filename, 'public');
                    $uploadedFiles[] = $filename;
                }
            }
        }
        
        // Stocker les noms des fichiers dans la base
        $data['fichier'] = !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null;
        
        // Créer le cours
        $course = Cours::create($data);
        
        $message = 'Cours créé avec succès.';
        if (count($uploadedFiles) > 0) {
            $message .= ' ' . count($uploadedFiles) . ' fichier(s) importé(s).';
        } else {
            $message .= ' Aucun fichier importé.';
        }
        
        return redirect()->route('admin.courses.index')->with('success', $message);
    }
    
    /**
     * Afficher un cours avec ses fichiers
     */
    public function show($id)
    {
        $course = Cours::with(['module.niveau', 'exercices', 'ressources'])->findOrFail($id);
        
        // Récupérer la liste des fichiers
        $fichiers = $course->fichier ? explode(',', $course->fichier) : [];
        $fichiers = array_filter($fichiers); // Supprimer les éléments vides
        
        return view('admin.courses.show', compact('course', 'fichiers'));
    }
    
    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $course = Cours::findOrFail($id);
        $modules = Module::with('niveau')->orderBy('ordre')->get();
        
        // Récupérer la liste des fichiers pour l'affichage
        $fichiers = $course->fichier ? explode(',', $course->fichier) : [];
        $fichiers = array_filter($fichiers);
        
        return view('admin.courses.edit', compact('course', 'modules', 'fichiers'));
    }
    
    /**
     * Mettre à jour un cours
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'titre' => 'required|string|max:100',
            'contenu' => 'required|string',
            'ordre' => 'integer|nullable',
            'statut' => 'required|in:brouillon,publie,archive',
            'fichiers.*' => 'nullable|file|mimes:pdf,doc,docx,zip,ppt,pptx|max:10240'
        ]);
        
        $course = Cours::findOrFail($id);
        $data = $request->except('fichiers');
        $uploadedFiles = [];
        
        // Gestion des fichiers multiples
        if ($request->hasFile('fichiers')) {
            // Supprimer les anciens fichiers
            if ($course->fichier) {
                $oldFiles = explode(',', $course->fichier);
                foreach ($oldFiles as $oldFile) {
                    if ($oldFile && Storage::disk('public')->exists('courses/' . $oldFile)) {
                        Storage::disk('public')->delete('courses/' . $oldFile);
                    }
                }
            }
            
            $files = $request->file('fichiers');
            if (!is_array($files)) {
                $files = [$files];
            }
            
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                    $file->storeAs('courses', $filename, 'public');
                    $uploadedFiles[] = $filename;
                }
            }
            
            $data['fichier'] = !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null;
        }
        
        $course->update($data);
        
        $message = 'Cours modifié avec succès.';
        if (count($uploadedFiles) > 0) {
            $message .= ' ' . count($uploadedFiles) . ' nouveau(x) fichier(s) importé(s).';
        }
        
        return redirect()->route('admin.courses.index')->with('success', $message);
    }
    
    /**
     * Supprimer un cours
     */
    public function destroy($id)
    {
        $course = Cours::withCount(['exercices', 'ressources'])->findOrFail($id);
        
        if ($course->exercices_count > 0 || $course->ressources_count > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', "Impossible de supprimer « {$course->titre} » : il contient encore {$course->exercices_count} exercice(s) et {$course->ressources_count} ressource(s). Supprimez-les d'abord.");
        }
        
        // Supprimer tous les fichiers associés
        if ($course->fichier) {
            $files = explode(',', $course->fichier);
            foreach ($files as $file) {
                if ($file && Storage::disk('public')->exists('courses/' . $file)) {
                    Storage::disk('public')->delete('courses/' . $file);
                }
            }
        }
        
        $course->delete();
        
        return redirect()->route('admin.courses.index')->with('success', 'Cours supprimé avec succès.');
    }
    
    /**
     * Action en masse sur les cours
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:cours,id',
            'action' => 'required|in:delete'
        ]);
        
        $courses = Cours::withCount(['exercices', 'ressources'])->whereIn('id', $request->courses)->get();
        
        $deleted = 0;
        $skipped = [];
        
        foreach ($courses as $course) {
            if ($course->exercices_count > 0 || $course->ressources_count > 0) {
                $skipped[] = $course->titre;
                continue;
            }
            
            if ($course->fichier) {
                $files = explode(',', $course->fichier);
                foreach ($files as $file) {
                    if ($file && Storage::disk('public')->exists('courses/' . $file)) {
                        Storage::disk('public')->delete('courses/' . $file);
                    }
                }
            }
            
            $course->delete();
            $deleted++;
        }
        
        $message = "{$deleted} cours supprimé(s).";
        if (count($skipped) > 0) {
            $message .= ' Ignorés (contiennent encore des exercices/ressources) : ' . implode(', ', $skipped) . '.';
        }
        
        return redirect()->route('admin.courses.index')->with('success', $message);
    }
}