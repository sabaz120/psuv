<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalSalaTecnicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_sala_tecnica', function (Blueprint $table) {
            $table->id();
            $table->string("cedula", 10);
            $table->string("nombre", 25);
            $table->string("apellido", 25);
            $table->string("telefono_principal", 11);
            $table->string("rol", 100);

            $table->unsignedBigInteger("municipio_id");
            $table->foreign("municipio_id")->references("id")->on("municipio");
            
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
        Schema::dropIfExists('personal_sala_tecnicas');
    }
}
