<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroVotacion extends Model
{
    protected $table="centro_votacion";
    use HasFactory;

    public function personalCaracterizacions(){

        return $this->hasMany(PersonalCaracterizacion::class);

    }

    public function centroVotacions(){

        return $this->hasMany(CentroVotacion::class);

    }

    public function jefeUbchs(){

        return $this->hasMany(JefeUbch::class);

    }

    public function metasUbchs(){

        return $this->hasMany(MetasUbch::class);

    }

    public function parroquia(){

        return $this->belongsTo(Parroquia::class);

    }

    public function descargaCuadernillo(){

        return $this->hasMany(DescargaCuadernillo::class);

    }

    public function electores(){

        return $this->hasMany(Elector::class);

    }

    public function votaciones(){

        return $this->hasMany(Votacion::class);

    }

    public function mesas(){

        return $this->hasMany(Mesa::class,"centro_votacion_id");

    }

}
