<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesNivelTerritorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_nivel_territorial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("roles_equipo_politico_id")->nullable();
            $table->foreign("roles_equipo_politico_id")->references("id")->on("roles_equipo_politico");
            $table->unsignedBigInteger("nivel_territorial_id")->nullable();
            $table->foreign("nivel_territorial_id")->references("id")->on("nivel_territorial");
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
        Schema::dropIfExists('roles_nivel_territorial');
    }
}
