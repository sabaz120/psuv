<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCierreCandidatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierre_candidato_votacion', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger("mesa_id");
            $table->unsignedBigInteger("candidatos_id");
            $table->unsignedBigInteger("eleccion_id");
            $table->integer("cantidad_voto");

            $table->foreign("mesa_id")->references("id")->on("mesa");
            $table->foreign("candidatos_id")->references("id")->on("candidatos");
            $table->foreign("eleccion_id")->references("id")->on("eleccion");

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
        Schema::dropIfExists('cierre_candidatos');
    }
}
