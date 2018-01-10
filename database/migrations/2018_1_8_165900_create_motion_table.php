<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->char('mac_address', 100);
            $table->char('url', 100);
            $table->dateTime('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motion');
    }
}