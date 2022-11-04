<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolesNivelTerritorialIdInJefesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jefe_ubch', function (Blueprint $table) {
            $table->unsignedBigInteger("roles_nivel_territorial_id")->nullable();
            $table->foreign("roles_nivel_territorial_id")->references("id")->on("roles_nivel_territorial");
        });
        Schema::table('jefe_comunidad', function (Blueprint $table) {
            $table->unsignedBigInteger("roles_nivel_territorial_id")->nullable();
            $table->foreign("roles_nivel_territorial_id")->references("id")->on("roles_nivel_territorial");
        });
        Schema::table('jefe_calle', function (Blueprint $table) {
            $table->unsignedBigInteger("roles_nivel_territorial_id")->nullable();
            $table->foreign("roles_nivel_territorial_id")->references("id")->on("roles_nivel_territorial");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jefes_tables', function (Blueprint $table) {
            //
        });
    }
}
