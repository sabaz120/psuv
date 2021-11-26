<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamiliarMovimiento extends Model
{
    use HasFactory;
    protected $table="familiar_movimiento";
    protected $fillable=[
        "personal_caracterizacion_id",
        "participacion_movimiento_id",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    public function participacionMovimiento(){
        return $this->belongsTo(ParticipacionMovimiento::class,"participacion_movimiento_id");
    }
}
