<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalSalaTecnica extends Model
{
    use HasFactory;

    protected $table = "personal_sala_tecnica";

    public function municipio(){

        return $this->belongsTo(Municipio::class);

    }

}
