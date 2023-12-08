<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalCaracterizacion extends Model
{
    protected $table="personal_caracterizacion";
    use HasFactory;
    protected $appends=[
        "full_name"
      ];
    protected $fillable = [
        "cedula",
        "nacionalidad",
        "primer_apellido",
        "segundo_apellido",
        "primer_nombre",
        "segundo_nombre",
        "sexo",
        "telefono_principal",
        "telefono_secundario",
        "fecha_nacimiento",
        "tipo_voto",
        "inhabilitado_politicio",
        "estado_id",
        "municipio_id",
        "parroquia_id",
        "centro_votacion_id",
        "partido_politico_id",
        "movilizacion_id",
        "jefe_familia_id"
    ];

    public function getFullNameAttribute()
    {
        $name = $this->primer_nombre;
        // if (!empty($this->segundo_nombre)) {
        //     $name .= ' ' .$this->segundo_nombre;
        // }
        if (!empty($this->primer_apellido)) {
            $name .= ' ' .$this->primer_apellido;
        }
        // if (!empty($this->segundo_apellido)) {
        //     $name .= ' ' .$this->segundo_apellido;
        // }
        return $name;
    }

    public function jefeUbchs(){

        return $this->hasMany(JefeUbch::class);

    }

    public function jefeComunidads(){

        return $this->hasMany(JefeComunidad::class);

    }

    public function jefeFamilia(){

        return $this->hasMany(JefeFamilia::class,"jefe_familia_id");

    }

    public function municipio(){

        return $this->belongsTo(Municipio::class);

    }

    public function parroquia(){

        return $this->belongsTo(Parroquia::class);

    }

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }

    public function partidoPolitico(){

        return $this->belongsTo(PartidoPolitico::class);

    }

    public function movilizacion(){

        return $this->belongsTo(Movilizacion::class);

    }
    public function jefeFamiliaOwner(){

        return $this->hasOne(JefeFamilia::class,"personal_caraterizacion_id");

    }
}
