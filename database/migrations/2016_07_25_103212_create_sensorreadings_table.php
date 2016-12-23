<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorreadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensorreadings', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('sensorreadinggroup_id');
            $table->uuid('logical_sensor_id');
            $table->double('rawvalue');
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
        Schema::drop('sensorreadings');
    }
}
