<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCierreMesaVotacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierre_mesa_votacion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('eleccion_id')->unsigned();
            $table->foreign('eleccion_id')->references('id')->on('eleccion')->onDelete('cascade');
            $table->bigInteger('mesa_id')->unsigned();
            $table->foreign('mesa_id')->references('id')->on('mesa')->onDelete('cascade');
            $table->bigInteger('candidatos_partido_politico_id')->unsigned();
            $table->foreign('candidatos_partido_politico_id')->references('id')->on('candidatos_partido_politico')->onDelete('cascade');
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
        Schema::dropIfExists('cierre_mesa_votacion');
    }
}
