<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModePaiement extends Model
{
    use HasFactory;
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_mode_paiement');
    }

}
