<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalComandoRegionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_comando_regional', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('personal_caracterizacion_id')->unsigned();
            $table->foreign('personal_caracterizacion_id')->references('id')->on('personal_caracterizacion')->onDelete('cascade');
            $table->bigInteger('comision_trabajo_id')->unsigned();
            $table->foreign('comision_trabajo_id')->references('id')->on('comision_trabajo')->onDelete('cascade');
            $table->bigInteger('responsabilidad_comando_id')->unsigned();
            $table->foreign('responsabilidad_comando_id')->references('id')->on('responsabilidad_comando')->onDelete('cascade');
           
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
        Schema::dropIfExists('personal_comando_regional');
    }
}
