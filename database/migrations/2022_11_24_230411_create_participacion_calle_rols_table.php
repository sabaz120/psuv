<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipacionCalleRolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participacion_calle_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("calle_id");
            $table->unsignedBigInteger("personal_caracterizacion_id");
            $table->date("fecha_participacion")->nullable();
            $table->foreign("calle_id")->references("id")->on("calle");
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
        Schema::dropIfExists('participacion_calle_roles');
    }
}
