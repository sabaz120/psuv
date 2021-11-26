<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalComandoParroquial extends Model
{
    use HasFactory;
    protected $table="personal_comando_parroquial";
    protected $fillable=[
        "personal_caracterizacion_id",
        "comision_trabajo_id",
        "responsabilidad_comando_id",
        "parroquia_id",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    public function comisionTrabajo(){
        return $this->belongsTo(ComisionTrabajo::class,"comision_trabajo_id");
    }
    public function responsabilidadComando(){
        return $this->belongsTo(ResponsabilidadComando::class,"responsabilidad_comando_id");
    }
    public function parroquia(){
        return $this->belongsTo(Parroquia::class,"parroquia_id");
    }
}
