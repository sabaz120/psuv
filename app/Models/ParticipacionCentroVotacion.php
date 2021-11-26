<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionCentroVotacion extends Model
{
    use HasFactory;

    protected $table = "participacion_centro_votacion";

    public function mesa(){
        return $this->belongsTo(Mesa::class);
    }
}
