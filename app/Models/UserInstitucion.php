<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInstitucion extends Model
{
    use HasFactory;
    protected $table="user_institucion";
    protected $fillable=[
        "user_id",
        "institucion_id",
    ];
    public function institucion(){

        return $this->belongsTo(Institucion::class);

    }
}
