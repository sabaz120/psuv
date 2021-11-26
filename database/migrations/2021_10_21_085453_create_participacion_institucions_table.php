<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipacionInstitucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participacion_institucion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('personal_caracterizacion_id')->unsigned();
            $table->foreign('personal_caracterizacion_id')->references('id')->on('personal_caracterizacion')->onDelete('cascade');
            $table->bigInteger('institucion_id')->unsigned();
            $table->foreign('institucion_id')->references('id')->on('institucion')->onDelete('cascade');
            $table->bigInteger('cargo_id')->unsigned();
            $table->foreign('cargo_id')->references('id')->on('cargo')->onDelete('cascade');
            $table->text("direccion")->nullable();
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
        Schema::dropIfExists('participacion_institucion');
    }
}
