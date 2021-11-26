<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamiliarTrabajador extends Model
{
    use HasFactory;
    protected $table="familiar_trabajador";
    protected $fillable=[
        "personal_caracterizacion_id",
        "participacion_institucion_id",
    ];
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
    public function participacionInstitucion(){
        return $this->belongsTo(ParticipacionInstitucion::class,"participacion_institucion_id");
    }
}
