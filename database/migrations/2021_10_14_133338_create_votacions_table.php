<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votacion', function (Blueprint $table) {
            $table->id();
            $table->integer("codigo_cuadernillo");
            $table->boolean("ejercio_voto")->default(false);

            $table->unsignedBigInteger("personal_caracterizacion_id");
            $table->unsignedBigInteger("eleccion_id");
            $table->foreign("personal_caracterizacion_id")->references("id")->on("personal_caracterizacion");
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
        Schema::dropIfExists('votacions');
    }
}
