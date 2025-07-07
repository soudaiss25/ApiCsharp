<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'adresse',
        'identifiant',
        'mot_de_passe',
        'status',
        'role'
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    // Exemple d'accessors
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }
}
