<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEleccionFieldOnCandidatosPartidoPoliticoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidatos_partido_politico', function (Blueprint $table) {
            $table->dropForeign('candidatos_partido_politico_eleccion_id_foreign');
            $table->dropColumn('eleccion_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidatos_partido_politico', function (Blueprint $table) {
            $table->bigInteger('eleccion_id')->unsigned();
            $table->foreign('eleccion_id')->references('id')->on('eleccion')->onDelete('cascade');
        });
    }
}
