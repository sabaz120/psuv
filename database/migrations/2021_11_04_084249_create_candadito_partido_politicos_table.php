<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandaditoPartidoPoliticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidatos_partido_politico', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('eleccion_id')->unsigned();
            $table->foreign('eleccion_id')->references('id')->on('eleccion')->onDelete('cascade');
            $table->bigInteger('candidatos_id')->unsigned();
            $table->foreign('candidatos_id')->references('id')->on('candidatos')->onDelete('cascade');
            $table->bigInteger('partido_politico_id')->unsigned();
            $table->foreign('partido_politico_id')->references('id')->on('partido_politico')->onDelete('cascade');
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
        Schema::dropIfExists('candidatos_partido_politico');
    }
}
