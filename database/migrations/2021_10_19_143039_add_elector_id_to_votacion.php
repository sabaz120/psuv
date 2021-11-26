<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElectorIdToVotacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votacion', function (Blueprint $table) {
            $table->bigInteger('elector_id')->unsigned()->nullable();
            $table->foreign('elector_id')->references('id')->on('elector')->onDelete('cascade');
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
