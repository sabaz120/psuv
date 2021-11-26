<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreCandidatoVotacion extends Model
{
    use HasFactory;

    protected $table = "cierre_candidato_votacion";

    public function candidato(){

        return $this->belongsTo(Candidato::class, "candidatos_id");

    }

}
