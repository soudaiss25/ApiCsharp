<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    use HasFactory;
    public function typeAppartement()
    {
        return $this->belongsTo(TypeAppartement::class, 'id_type_appartement');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'id_proprietaire');
    }

}
