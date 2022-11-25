<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipacionComunidadRolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participacion_comunidad_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("comunidad_id");
            $table->unsignedBigInteger("personal_caracterizacion_id");
            $table->date("fecha_participacion")->nullable();
            $table->foreign("comunidad_id")->references("id")->on("comunidad");
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
        Schema::dropIfExists('participacion_comunidad_roles');
    }
}
