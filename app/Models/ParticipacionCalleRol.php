<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionCalleRol extends Model
{
    use HasFactory;
    protected $table="participacion_calle_roles";
    protected $fillable=[
        "calle_id",
        "personal_caracterizacion_id",
        "fecha_participacion"
    ];
}
