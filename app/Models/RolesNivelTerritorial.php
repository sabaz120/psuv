<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesNivelTerritorial extends Model
{
    use HasFactory;
    protected $table="roles_nivel_territorial";
    protected $fillable=[
        "roles_equipo_politico_id",
        "nivel_territorial_id",
    ];
    
    public function RolesEquipoPolitico(){

        return $this->belongsTo(RolesEquipoPolitico::class,"roles_equipo_politico_id");

    }
}
