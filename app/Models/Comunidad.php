<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table="comunidad";
    use HasFactory;

    public function jefeComunidads(){

        return $this->belongsTo(JefeComunidad::class);

    }

    public function parroquia(){

        return $this->belongsTo(Parroquia::class,"parroquia_id");

    }

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class,"centro_votacion_id");

    }
}
