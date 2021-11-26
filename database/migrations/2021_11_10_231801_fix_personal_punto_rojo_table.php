<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPersonalPuntoRojoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_punto_rojo', function (Blueprint $table) {
            $table->string("cedula")->default("0");
            $table->dropColumn("telefono");
            $table->string("telefono_principal")->default("0");
            $table->string("telefono_secundario")->nullable();
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
            $table->dropColumn("cedula");
            $table->string("telefono");
            $table->dropColumn("telefono_principal");
            $table->dropColumn("telefono_secundario");
        });
    }
}
