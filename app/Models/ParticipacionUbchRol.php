<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionUbchRol extends Model
{
    use HasFactory;
    protected $table="participacion_ubch_roles";
    protected $fillable=[
        "centro_votacion_id",
        "personal_caracterizacion_id",
        "fecha_participacion"
    ];
}
