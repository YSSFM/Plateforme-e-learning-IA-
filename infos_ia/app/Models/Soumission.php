<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soumission extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'exercice_id',
        'fichier',
        'note',
        'feedback',
        'tentative',
        'statut',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'note' => 'integer',
            'tentative' => 'integer',
        ];
    }

    /**
     * Relation : Une soumission appartient à un utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Une soumission appartient à un exercice
     */
    public function exercice()
    {
        return $this->belongsTo(Exercice::class);
    }

    /**
     * Vérifier si la soumission a une note
     */
    public function hasNote()
    {
        return !is_null($this->note);
    }

    /**
     * Vérifier si la soumission est corrigée
     */
    public function isCorrigee()
    {
        return $this->statut === 'corrige';
    }
}