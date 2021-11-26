<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipacionMovimiento extends Model
{
    use HasFactory;
    protected $table="participacion_movimiento";
    protected $fillable=[
        "personal_caracterizacion_id",
        "movimiento_id",
        "cargo_id",
        "nivel_estructura_id",
        "direccion",
        "area_atencion",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    public function cargo(){
        return $this->belongsTo(Cargo::class,"cargo_id");
    }
    public function movimiento(){
        return $this->belongsTo(Movimiento::class,"movimiento_id");
    }
    public function nivelEstructura(){
        return $this->belongsTo(NivelEstructura::class,"nivel_estructura_id");
    }
    public function familiares(){
        return $this->hasMany(FamiliarMovimiento::class,"participacion_movimiento_id");
    }
}
