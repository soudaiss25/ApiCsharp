<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    public function contratLocation()
    {
        return $this->belongsTo(ContratLocation::class, 'id_contrat_location');
    }

    public function modePaiement()
    {
        return $this->belongsTo(ModePaiement::class, 'id_mode_paiement');
    }

}
