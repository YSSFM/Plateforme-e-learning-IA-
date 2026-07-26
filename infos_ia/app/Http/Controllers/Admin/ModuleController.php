<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Niveau;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::with('niveau')->orderBy('ordre')->paginate(20);
        return view('admin.modules.index', compact('modules'));
    }

    public function create()
    {
        $niveaux = Niveau::orderBy('ordre')->get();
        return view('admin.modules.create', compact('niveaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'niveau_id' => 'required|exists:niveaux,id',
            'ordre' => 'integer|nullable'
        ]);
        
        Module::create($request->all());
        return redirect()->route('admin.modules.index')->with('success', 'Module créé avec succès.');
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        $niveaux = Niveau::orderBy('ordre')->get();
        return view('admin.modules.edit', compact('module', 'niveaux'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|string|max:100',
            'description' => 'nullable|string',
            'niveau_id' => 'required|exists:niveaux,id',
            'ordre' => 'integer|nullable'
        ]);
        
        $module = Module::findOrFail($id);
        $module->update($request->all());
        return redirect()->route('admin.modules.index')->with('success', 'Module modifié avec succès.');
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);

        $coursCount = $module->cours()->count();
        if ($coursCount > 0) {
            return redirect()->route('admin.modules.index')
                ->with('error', "Impossible de supprimer ce module : il contient encore {$coursCount} cours. Supprimez ou déplacez d'abord ces cours.");
        }

        $module->delete();
        return redirect()->route('admin.modules.index')->with('success', 'Module supprimé avec succès.');
    }
}