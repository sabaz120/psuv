<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedNullableToCargoFieldOnPersonalPuntoRojoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_punto_rojo', function (Blueprint $table) {
            $table->string("cargo")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_punto_rojo', function (Blueprint $table) {
            $table->string("cargo")->change();
        });
    }
}
