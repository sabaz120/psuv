<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestigoMesa extends Model
{
    use HasFactory;
    protected $table="testigo_mesa";
    protected $fillable=[
        "mesa_id",
        "personal_caracterizacion_id",
    ];
    
    public function personalCaracterizacion(){
        return $this->belongsTo(PersonalCaracterizacion::class,"personal_caracterizacion_id");
    }
      
    public function mesa(){
        return $this->belongsTo(Mesa::class,"mesa_id");
    }
}
