<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEleccionIdToMetasUbch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas_ubches', function (Blueprint $table) {
            $table->unsignedBigInteger("eleccion_id")->nullable();
            $table->foreign("eleccion_id")->references("id")->on("eleccion");
            $table->integer("cantidad_electores")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas_ubch', function (Blueprint $table) {
            //
        });
    }
}
