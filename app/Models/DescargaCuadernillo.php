<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescargaCuadernillo extends Model
{
    use HasFactory;

    public function centroVotacion(){

        return $this->belongsTo(CentroVotacion::class);

    }

}
