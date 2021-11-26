<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReporteVotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporte_voto', function (Blueprint $table) {
            $table->id();
            $table->string("reporta", 250);
            $table->unsignedBigInteger("votacion_id");
            $table->foreign("votacion_id")->references("id")->on("votacion");
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
        Schema::dropIfExists('reporte_votos');
    }
}
