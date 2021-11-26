<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidatoPartidoPolitico extends Model
{
    use HasFactory;
    protected $table="candidatos_partido_politico";
    protected $fillable=[
        "candidatos_id",
        "partido_politico_id",
    ];

    public function cierrePartidoVotacion(){

        return $this->belongsTo(CandidatoPartidoPolitico::class, "candidatos_partido_politico_id");

    }

    public function candidato(){

        return $this->belongsTo(Candidato::class, "candidatos_id");

    }

    public function partidoPolitico(){

        return $this->belongsTo(PartidoPolitico::class);

    }

}
