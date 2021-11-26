<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePersonalCaracterizacionIdFromInstitucion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institucion', function (Blueprint $table) {
            $table->dropForeign('institucion_personal_caracterizacion_id_foreign');
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
        Schema::table('institucion', function (Blueprint $table) {
            //
        });
    }
}
