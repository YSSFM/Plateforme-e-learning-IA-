<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'libelle',
        'ordre',
    ];

    /**
     * Relation : Un niveau a plusieurs utilisateurs
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relation : Un niveau a plusieurs modules
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Retourne le code du niveau (S1, S2, S3, S4)
     */
    public function getCodeAttribute($value)
    {
        return strtoupper($value);
    }
}