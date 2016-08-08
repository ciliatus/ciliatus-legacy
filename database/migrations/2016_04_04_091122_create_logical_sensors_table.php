<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogicalSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logical_sensors', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->uuid('physical_sensor_id');
            #$table->foreign('physical_sensor_id')->references('id')->on('physical_sensors');
            $table->string('type');
            $table->double('rawvalue');
            $table->double('rawvalue_lowerlimit');
            $table->double('rawvalue_upperlimit');
            $table->integer('soft_state_duration_minutes')->default(env('DEFAULT_SOFT_STATE_DURATION_MINUTES', 10));
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
        Schema::drop('logical_sensors');
    }
}
