<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    use HasFactory;

    public function cierreCandidatoVotacion(){

        return $this->belongsTo(CierreCandidatoVotacion::class, "candidatos_id");

    }
    protected $table="candidatos";
    protected $fillable=[
        "nombre",
        "apellido",
        "foto",
        "cargo_eleccion",
        "municipio_id",
        "eleccion_id",
    ];

    protected $appends=[
        "partidosPoliticosNombres",
        "fullName"
    ];

    public function getFullNameAttribute()
    {
        $name = $this->nombre;
        if (!empty($this->apellido)) {
            $name .= ' ' .$this->apellido;
        }
        return $name;
    }

    public function getFotoAttribute($value)
    {
        return url($value);
    }


    public function partidos(){
        return $this->hasMany(CandidatoPartidoPolitico::class,'candidatos_id');
    }
    
    public function partidosPoliticos(){
        return $this->belongsToMany(PartidoPolitico::class,'candidatos_partido_politico', 'candidatos_id', 'partido_politico_id');
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class,'municipio_id');
    }

    public function getPartidosPoliticosNombresAttribute(){
        $partidos=$this->partidosPoliticos;
        $names="";
        foreach($partidos as $partido){
            if(!$names)
                $names=$partido->nombre;
            else{
                $names.=", ".$partido->nombre;
            }
        }//
        return $names;
    }//

    public function cierrePartidoPolitico(){

        return $this->hasMany(CandidatoPartidoPolitico::class, "candidatos_id");

    }

}
