<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalPuntoRojo extends Model
{
    use HasFactory;
    protected $table="personal_punto_rojo";
    protected $appends=[
        "fullName"
    ];

    protected $fillable=[
        "nombre",
        "apellido",
        "cedula",
        "telefono_principal",
        "telefono_secundario",
        "centro_votacion_id",
    ];

    public function getFullNameAttribute()
    {
        $name = $this->nombre;
        if (!empty($this->apellido)) {
            $name .= ' ' .$this->apellido;
        }
        return $name;
    }

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }


}
