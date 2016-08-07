<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogicalSensorThresholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logical_sensor_thresholds', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('logical_sensor_id');
            $table->double('rawvalue_lowerlimit')->nullable();
            $table->double('rawvalue_upperlimit')->nullable();
            $table->time('starts_at');
            $table->boolean('active');
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
        Schema::drop('logical_sensor_thresholds');
    }
}
