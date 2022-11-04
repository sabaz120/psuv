<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelTerritorial extends Model
{
    use HasFactory;
    protected $table="nivel_territorial";
    protected $fillable=[
        "nombre_nivel",
    ];
}
