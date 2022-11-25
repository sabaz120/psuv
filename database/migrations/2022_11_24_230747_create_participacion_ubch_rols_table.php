<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipacionUbchRolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participacion_ubch_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("centro_votacion_id");
            $table->unsignedBigInteger("personal_caracterizacion_id");
            $table->date("fecha_participacion")->nullable();
            $table->foreign("centro_votacion_id")->references("id")->on("centro_votacion");
            $table->foreign("personal_caracterizacion_id")->references("id")->on("personal_caracterizacion");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participacion_ubch_roles');
    }
}
