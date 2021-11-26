<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipacionCentroVotacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participacion_centro_votacion', function (Blueprint $table) {
            $table->id();
            $table->time("hora");
            $table->integer("cantidad");
            $table->bigInteger('eleccion_id')->unsigned();
            $table->foreign('eleccion_id')->references('id')->on('eleccion')->onDelete('cascade');
            $table->bigInteger('mesa_id')->unsigned();
            $table->foreign('mesa_id')->references('id')->on('mesa')->onDelete('cascade');
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
        Schema::dropIfExists('participacion_centro_votacion');
    }
}
