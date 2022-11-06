<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteJefeComunidadIdInJefeCalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jefe_calle', function (Blueprint $table) {
            $table->dropForeign('jefe_calle_jefe_comunidad_id_foreign');
            $table->dropColumn('jefe_comunidad_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jefe_calle', function (Blueprint $table) {
            //
        });
    }
}
