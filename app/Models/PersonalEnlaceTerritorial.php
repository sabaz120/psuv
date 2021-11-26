<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalEnlaceTerritorial extends Model
{
    use HasFactory;
    protected $table="personal_enlace_territorial";
    protected $fillable=[
        "personal_caracterizacion_id",
        "centro_votacion_id",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }
}
