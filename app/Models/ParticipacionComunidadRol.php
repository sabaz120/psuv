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
}
