<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_personne';
    protected $fillable = [
        'nom', 'prenom', 'email', 'telephone', 'cni'
    ];
}

