<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratLocation extends Model
{
    use HasFactory;
    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'id_locataire');
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'id_appartement');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_contrat_location');
    }

}
