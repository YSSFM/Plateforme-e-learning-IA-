<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée (car la table s'appelle "cours" et non "cours")
     */
    protected $table = 'cours';

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'module_id',
        'titre',
        'contenu',
        'ordre',
        'statut',
        'fichier',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'statut' => 'string',
        ];
    }

    /**
     * Relation : Un cours appartient à un module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relation : Un cours a plusieurs exercices
     */
    public function exercices()
    {
        return $this->hasMany(Exercice::class);
    }

    /**
     * Relation : Un cours a plusieurs ressources
     */
    public function ressources()
    {
        return $this->hasMany(Ressource::class);
    }

    /**
     * Relation : Un cours a plusieurs progressions
     */
    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }

    /**
     * Vérifier si le cours est publié
     */
    public function isPublie()
    {
        return $this->statut === 'publie';
    }
}