<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Votacion extends Model
{
    use HasFactory;

    protected $table = "votacion";

    public function elector(){

        return $this->belongsTo(Elector::class);

    }

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }

}
