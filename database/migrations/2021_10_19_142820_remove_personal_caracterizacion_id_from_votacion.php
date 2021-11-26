<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePersonalCaracterizacionIdFromVotacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votacion', function (Blueprint $table) {
            $table->dropForeign('votacion_personal_caracterizacion_id_foreign');
            $table->dropColumn('personal_caracterizacion_id');
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
