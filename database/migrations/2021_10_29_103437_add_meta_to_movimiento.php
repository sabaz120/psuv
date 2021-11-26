<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaToMovimiento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimiento', function (Blueprint $table) {
            $table->integer("meta")->default(0);
            $table->string("responsable", 100)->default("");
            $table->string("telefono", 12)->default("");
            $table->unsignedBigInteger("municipio_id")->default("1");
            $table->foreign("municipio_id")->references("id")->on("municipio");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimiento', function (Blueprint $table) {
            //
        });
    }
}
