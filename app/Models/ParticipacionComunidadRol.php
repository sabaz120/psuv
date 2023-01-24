<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionComunidadRol extends Model
{
    use HasFactory;
    protected $table="participacion_comunidad_roles";
    protected $fillable=[
        "comunidad_id",
        "personal_caracterizacion_id",
        "fecha_participacion"
    ];
    public function comunidad(){
        return $this->belongsTo(Comunidad::class,"comunidad_id");
    }
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
}
