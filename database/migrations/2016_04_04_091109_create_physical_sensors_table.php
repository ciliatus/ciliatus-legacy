<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysicalSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physical_sensors', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('controlunit_id');
            $table->foreign('controlunit_id')->references('id')->on('controlunits');
            $table->string('belongsTo_type');
            $table->uuid('belongsTo_id');
            $table->string('name');
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
        Schema::drop('physical_sensors');
    }
}
