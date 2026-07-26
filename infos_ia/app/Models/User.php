<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'niveau_id',
        'statut',
        'role',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation : Un utilisateur appartient à un niveau (S1, S2, S3, S4)
     */
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    /**
     * Relation : Un utilisateur a plusieurs soumissions
     */
    public function soumissions()
    {
        return $this->hasMany(Soumission::class);
    }

    /**
     * Relation : Un utilisateur a plusieurs progressions
     */
    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }

    /**
     * Relation : Un utilisateur a plusieurs remarques (reçues)
     */
    public function remarquesRecues()
    {
        return $this->hasMany(Remarque::class, 'user_id');
    }

    /**
     * Relation : Un utilisateur a plusieurs remarques (données - admin)
     */
    public function remarquesDonnees()
    {
        return $this->hasMany(Remarque::class, 'admin_id');
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est actif (non bloqué)
     */
    public function isActive()
    {
        return $this->statut === 'actif';
    }
}