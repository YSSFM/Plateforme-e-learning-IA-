<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cours_id',
        'statut',
        'derniere_activite',
        'temps_passe'
    ];

    protected $casts = [
        'derniere_activite' => 'datetime',
        'temps_passe' => 'integer'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    // Mettre à jour automatiquement la progression
    public static function updateProgress($userId, $coursId, $action = 'view')
    {
        $progression = self::firstOrCreate(
            ['user_id' => $userId, 'cours_id' => $coursId],
            ['statut' => 'non_commence', 'derniere_activite' => now()]
        );

        // Si l'action est 'view' et que le statut est 'non_commence' -> 'en_cours'
        if ($action === 'view' && $progression->statut === 'non_commence') {
            $progression->statut = 'en_cours';
        }

        // Si l'action est 'complete' -> 'termine'
        if ($action === 'complete') {
            $progression->statut = 'termine';
        }

        $progression->derniere_activite = now();
        $progression->save();

        return $progression;
    }

    // Vérifier si un cours est terminé (tous les exercices soumis)
    public static function checkCourseCompletion($userId, $coursId)
    {
        $cours = Cours::with('exercices')->find($coursId);
        
        if (!$cours || $cours->exercices->isEmpty()) {
            return false;
        }

        $exerciceIds = $cours->exercices->pluck('id')->toArray();
        $submittedCount = Soumission::where('user_id', $userId)
            ->whereIn('exercice_id', $exerciceIds)
            ->count();

        return $submittedCount >= count($exerciceIds);
    }
}