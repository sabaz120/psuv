<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescargaCuadernillosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descarga_cuadernillos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("eleccion_id");
            $table->unsignedBigInteger("centro_votacion_id");
            $table->string("file")->nullable();
            $table->boolean("descargado")->default(false);

            $table->foreign("eleccion_id")->references("id")->on("eleccion");
            $table->foreign("centro_votacion_id")->references("id")->on("centro_votacion");

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
        Schema::dropIfExists('descarga_cuadernillos');
    }
}
