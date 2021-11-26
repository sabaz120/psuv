<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;
    protected $table="institucion";
    protected $fillable=[
        "nombre",
        "personal_activo_nomina",
        "personal_caracterizacion_id",
        "telefono",
        "municipio_id",
    ];
    //Representante de la institucion
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    
    public function municipio(){

        return $this->belongsTo(Municipio::class,"municipio_id");

    }
}
