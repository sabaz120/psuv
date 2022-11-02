<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesEquipoPolitico extends Model
{
    use HasFactory;
    protected $table="roles_equipo_politico";
    protected $fillable=[
        "nombre_rol",
    ];

    
    public function rolNivelTerritorial(){

        return $this->hasOne(RolesNivelTerritorial::class,"roles_equipo_politico_id");

    }
}
