<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;
    protected $table="mesa";
    protected $fillable=[
        "numero_mesa",
        "transmision",
        "hora_transmision",
        "eleccion_id",
        "centro_votacion_id",
        "observacion",
    ];
    
    public function centroVotacion(){
        return $this->belongsTo(CentroVotacion::class,"centro_votacion_id");
    }
    
    public function participacionCentroVotacions(){
        return $this->hasMany(ParticipacionCentroVotacion::class);
    }


}
