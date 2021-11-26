<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCentroVotacionIdToVotacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votacion', function (Blueprint $table) {
            $table->unsignedBigInteger("centro_votacion_id");
            $table->foreign("centro_votacion_id")->references("id")->on("centro_votacion");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votacion', function (Blueprint $table) {
            //
        });
    }
}
