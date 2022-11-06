<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCentroVotacionIdFieldInComunidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comunidad', function (Blueprint $table) {
            $table->dropForeign('comunidad_parroquia_id_foreign');
            $table->dropColumn('parroquia_id');
            $table->bigInteger('centro_votacion_id')->unsigned()->nullable();
            $table->foreign('centro_votacion_id')->references('id')->on('centro_votacion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comunidad', function (Blueprint $table) {
            //
        });
    }
}
