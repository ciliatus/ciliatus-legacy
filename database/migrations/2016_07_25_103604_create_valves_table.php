<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valves', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('controlunit_id')->nullable();
            #$table->foreign('controlunit_id')->references('id')->on('controlunits');
            $table->uuid('terrarium_id')->nullable();
            #$table->foreign('terrarium_id')->references('id')->on('terraria');
            $table->uuid('pump_id');
            $table->string('name');
            $table->string('state')->default('unknown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('valves');
    }
}
