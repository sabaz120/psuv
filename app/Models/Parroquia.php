<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    protected $table="parroquia";
    use HasFactory;

    public function personalCaracterizacions(){

        return $this->hasMany(PersonalCaracterizacion::class);

    }

    public function electores(){

        return $this->hasMany(Elector::class);

    }

    public function municipio(){

        return $this->belongsTo(Municipio::class);

    }

    public function comunidades(){

        return $this->hasMany(Comunidad::class);

    }

    public function centroVotacions(){

        return $this->hasMany(CentroVotacion::class);

    }

    public function metasUbchs(){

        return $this->hasMany(MetasUbch::class);

    }



}
