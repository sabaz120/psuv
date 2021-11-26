<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsabilidadComando extends Model
{
    use HasFactory;
    protected $table="responsabilidad_comando";
    protected $fillable=[
        "nombre",
        "descripcion",
    ];
}
