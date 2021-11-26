<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalComandoMunicipal extends Model
{
    use HasFactory;
    protected $table="personal_comando_municipal";
    protected $fillable=[
        "personal_caracterizacion_id",
        "comision_trabajo_id",
        "responsabilidad_comando_id",
        "municipio_id",
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
    public function municipio(){
        return $this->belongsTo(Municipio::class,"municipio_id");
    }
}
