<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'titre',
        'description',
        'niveau_id',
        'ordre',
    ];

    /**
     * Relation : Un module appartient à un niveau
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    /**
     * Relation : Un module a plusieurs cours
     */
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
}