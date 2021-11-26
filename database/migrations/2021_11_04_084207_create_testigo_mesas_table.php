<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestigoMesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testigo_mesa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('mesa_id')->unsigned();
            $table->foreign('mesa_id')->references('id')->on('mesa')->onDelete('cascade');
            $table->bigInteger('eleccion_id')->unsigned();
            $table->foreign('eleccion_id')->references('id')->on('eleccion')->onDelete('cascade');
            $table->bigInteger('personal_caracterizacion_id')->unsigned();
            $table->foreign('personal_caracterizacion_id')->references('id')->on('personal_caracterizacion')->onDelete('cascade');
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
        Schema::dropIfExists('testigo_mesa');
    }
}
