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
            $table->string('belongsTo_type');
            $table->uuid('belongsTo_id');
            $table->string('name');
            $table->string('model');
            $table->timestamp('heartbeat_at');
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
