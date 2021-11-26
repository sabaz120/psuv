<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table="municipio";
    use HasFactory;

    public function personalCaracterizacions(){

        return $this->hasMany(PersonalCaracterizacion::class);

    }

    public function electores(){

        return $this->hasMany(Elector::class);

    }

    public function parroquias(){

        return $this->hasMany(Parroquia::class);

    }

    public function metasUbchs(){

        return $this->hasMany(MetasUbch::class);

    }

    public function personalSalaTecnicas(){

        return $this->hasMany(PersonalSalaTecnica::class);

    }
}
