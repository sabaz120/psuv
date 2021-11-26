<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetasUbch extends Model
{
    use HasFactory;

    public function parroquia(){

        return $this->belongsTo(Parroquia::class);

    }

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }

    public function municipio(){

        return $this->belongsTo(Municipio::class);

    }

}

