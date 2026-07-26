<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cours_id',
        'titre',
        'enonce',
        'fichier_enonce',
        'type',
        'correction',
        'fichier_correction',
        'points_max',
        'deadline',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'points_max' => 'integer',
        ];
    }

    /**
     * Relation : Un exercice appartient à un cours
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    /**
     * Relation : Un exercice a plusieurs soumissions
     */
    public function soumissions()
    {
        return $this->hasMany(Soumission::class);
    }

    /**
     * Vérifier si l'exercice a une deadline dépassée
     */
    public function isDeadlineDepassee()
    {
        if (!$this->deadline) {
            return false;
        }
        return now()->gt($this->deadline);
    }
}