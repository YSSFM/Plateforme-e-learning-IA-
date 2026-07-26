<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cours;
use App\Models\Soumission;
use App\Models\Module;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord admin
     */
    public function index()
    {
        // Statistiques pour le dashboard
        $totalUsers = User::count();
        $totalCourses = Cours::count();
        $totalModules = Module::count();
        $pendingSubmissions = Soumission::where('statut', 'soumis')->count();
        
        // Récupérer les derniers utilisateurs
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        
        // Récupérer les dernières soumissions
        $recentSubmissions = Soumission::with(['user', 'exercice'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCourses', 
            'totalModules',
            'pendingSubmissions',
            'recentUsers',
            'recentSubmissions'
        ));
    }
}