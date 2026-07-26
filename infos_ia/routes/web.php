<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\RemarkController as AdminRemarkController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil publique
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes d'authentification (fournies par Breeze)
require __DIR__.'/auth.php';

// Routes protégées (nécessitent connexion)
Route::middleware(['auth'])->group(function () {
    
    // Redirection après connexion selon le rôle
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Vérifier si l'utilisateur a un niveau, sinon rediriger vers choix du niveau
        if (!$user->niveau_id) {
            return redirect()->route('user.profile.edit')->with('info', 'Veuillez sélectionner votre niveau.');
        }
        
        return redirect()->route('user.dashboard');
    })->name('dashboard');
    
    // =============================================
    // ROUTES ADMIN
    // =============================================
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestion des modules
        Route::resource('modules', AdminModuleController::class);
        
        // Gestion des cours
        Route::resource('courses', AdminCourseController::class);
        Route::post('/courses/bulk-action', [AdminCourseController::class, 'bulkAction'])->name('courses.bulk-action');
        
        // Gestion des exercices
        Route::resource('exercices', AdminExerciseController::class);
        
        // Gestion des utilisateurs
        Route::resource('users', AdminUserController::class)->except(['create', 'store', 'edit']);
        Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('/users/{id}/block', [AdminUserController::class, 'block'])->name('users.block');
        Route::post('/users/{id}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
        
        // Gestion des soumissions
       // Gestion des soumissions
        Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{id}/grade', [AdminSubmissionController::class, 'show'])->name('submissions.grade');
        Route::post('/submissions/{id}/grade', [AdminSubmissionController::class, 'grade'])->name('submissions.grade.store');
        Route::delete('/submissions/{id}', [AdminSubmissionController::class, 'destroy'])->name('submissions.destroy');
        
        // Gestion des remarques
        Route::get('/remarks', [AdminRemarkController::class, 'index'])->name('remarks.index');
        Route::post('/remarks', [AdminRemarkController::class, 'store'])->name('remarks.store');
        Route::delete('/remarks/{id}', [AdminRemarkController::class, 'destroy'])->name('remarks.destroy');
    });
    
    // =============================================
    // ROUTES UTILISATEUR
    // =============================================
    Route::prefix('user')->name('user.')->group(function () {
        
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        
        // Modules
        Route::get('/modules', [App\Http\Controllers\User\ModuleController::class, 'index'])->name('modules.index');
        Route::get('/modules/{id}', [App\Http\Controllers\User\ModuleController::class, 'show'])->name('modules.show');
        
        // Cours
        Route::get('/courses/{id}', [App\Http\Controllers\User\CourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{id}/progress', [App\Http\Controllers\User\CourseController::class, 'updateProgress'])->name('courses.progress');
        
        // Exercices
        Route::get('/exercises', [App\Http\Controllers\User\ExerciseController::class, 'index'])->name('exercises.index');
        Route::get('/exercises/{id}', [App\Http\Controllers\User\ExerciseController::class, 'show'])->name('exercises.show');
        Route::post('/exercises/{id}/submit', [App\Http\Controllers\User\ExerciseController::class, 'submit'])->name('exercises.submit');
        
        // Soumissions
        Route::get('/submissions', [App\Http\Controllers\User\SubmissionController::class, 'index'])->name('submissions.index');
        Route::delete('/submissions/{id}', [App\Http\Controllers\User\ExerciseController::class, 'withdraw'])->name('submissions.withdraw');
        
        // Progression
        Route::get('/progress', [App\Http\Controllers\User\ProgressController::class, 'index'])->name('progress.index');
        
        // Profil
        Route::get('/profile', [App\Http\Controllers\User\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/choose-level', [App\Http\Controllers\User\ProfileController::class, 'chooseLevel'])->name('profile.choose-level');
    });
});