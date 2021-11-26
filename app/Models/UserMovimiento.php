<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMovimiento extends Model
{
    use HasFactory;
    protected $table="user_movimiento";
    protected $fillable=[
        "user_id",
        "movimiento_id",
    ];
    public function movimiento(){

        return $this->belongsTo(Movimiento::class);

    }
}
