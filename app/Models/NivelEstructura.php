<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelEstructura extends Model
{
    use HasFactory;
    protected $table="nivel_estructura";
    protected $fillable=[
        "nombre_nivel"
    ];
}
