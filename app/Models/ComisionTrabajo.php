<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComisionTrabajo extends Model
{
    use HasFactory;
    protected $table="comision_trabajo";
    protected $fillable=[
        "nombre_comision",
        "descripcion",
    ];

}
