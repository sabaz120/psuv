<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteVoto extends Model
{
    use HasFactory;

    protected $table = "reporte_voto";
    protected $fillable = [
        "reporta",
        "votacion_id",
    ];
}
