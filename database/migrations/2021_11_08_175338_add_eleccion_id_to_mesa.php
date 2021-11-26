<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddEleccionIdToMesa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mesa', function (Blueprint $table) {
            //$table->unsignedBigInteger("eleccion_id");
            //$table->foreign("eleccion_id")->references("id")->on("eleccion");
            $table->text("observacion");
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mesa', function (Blueprint $table) {
            //
        });
    }
}
