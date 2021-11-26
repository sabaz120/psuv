<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipacionMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participacion_movimiento', function (Blueprint $table) {
            $table->id();
            $table->longText("direccion")->nullable();
            $table->text("area_atencion");
            $table->bigInteger('personal_caracterizacion_id')->unsigned();
            $table->foreign('personal_caracterizacion_id')->references('id')->on('personal_caracterizacion')->onDelete('cascade');
            $table->bigInteger('cargo_id')->unsigned();
            $table->foreign('cargo_id')->references('id')->on('cargo')->onDelete('cascade');
            $table->bigInteger('movimiento_id')->unsigned();
            $table->foreign('movimiento_id')->references('id')->on('movimiento')->onDelete('cascade');
            $table->bigInteger('nivel_estructura_id')->unsigned();
            $table->foreign('nivel_estructura_id')->references('id')->on('nivel_estructura')->onDelete('cascade');
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
        Schema::dropIfExists('participacion_movimiento');
    }
}
