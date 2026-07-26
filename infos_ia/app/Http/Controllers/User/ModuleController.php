<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $modules = Module::with('niveau')
            ->when($user->niveau_id, function ($query) use ($user) {
                $query->where('niveau_id', $user->niveau_id);
            })
            ->orderBy('ordre')
            ->paginate(12);
        
        return view('user.modules.index', compact('modules'));
    }
    
    public function show($id)
    {
        $module = Module::with(['cours' => function($query) {
            $query->where('statut', 'publie')->orderBy('ordre');
        }])->findOrFail($id);
        
        return view('user.modules.show', compact('module'));
    }
}