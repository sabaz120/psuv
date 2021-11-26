<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JefeCalle extends Model
{
    use HasFactory;
    protected $table="jefe_calle";
    protected $fillable=[
        "calle_id",
        "personal_caraterizacion_id",
        "jefe_comunidad_id",
    ];
    public function calle(){
        return $this->belongsTo(Calle::class);
    }
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caraterizacion_id");
    }
    public function JefeComunidad(){
        return $this->belongsTo(JefeComunidad::class);
    }
    public function jefeFamilias(){
        return $this->hasMany(JefeFamilia::class,"jefe_calle_id");
    }

    public function calles(){
        return $this->hasMany(JefeCalle::class,"personal_caraterizacion_id","personal_caraterizacion_id");
    }
}
