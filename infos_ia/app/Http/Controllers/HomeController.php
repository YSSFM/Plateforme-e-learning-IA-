<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil publique
     */
    public function index()
    {
        // Récupérer quelques modules pour les afficher sur la page d'accueil
        $modules = Module::with('niveau')->orderBy('ordre')->take(6)->get();
        
        return view('home', compact('modules'));
    }
}