<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cours_id',
        'type',
        'titre',
        'url',
    ];

    /**
     * Relation : Une ressource appartient à un cours
     */
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}