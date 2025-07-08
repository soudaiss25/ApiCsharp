<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'id_personne',
        'identifiant',
        'mot_de_passe',
        'status',
        'role'
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'id_personne');
    }
}
