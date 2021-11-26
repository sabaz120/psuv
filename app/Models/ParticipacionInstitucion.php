<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionInstitucion extends Model
{
    use HasFactory;
    protected $table="participacion_institucion";
    protected $fillable=[
        "personal_caracterizacion_id",
        "institucion_id",
        "cargo_id",
        "direccion",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    public function institucion(){
        return $this->belongsTo(Institucion::class,"institucion_id");
    }
    public function cargo(){
        return $this->belongsTo(Cargo::class,"cargo_id");
    }
    public function familiares(){
        return $this->hasMany(FamiliarTrabajador::class,"participacion_institucion_id");
    }
}
