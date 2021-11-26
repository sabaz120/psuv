<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierrePartidoVotacion extends Model
{
    use HasFactory;

    protected $table = "cierre_partido_votacion";

    public function candidatoPartidoPolitico(){

        return $this->belongsTo(CandidatoPartidoPolitico::class, "candidatos_partido_politico_id");

    }

}
