<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->string('display_name');
            $table->boolean('notifications_enabled');
            $table->boolean('humidity_critical');
            $table->boolean('temperature_critical');
            $table->boolean('heartbeat_critical');
            $table->double('cooked_humidity_percent');
            $table->double('cooked_temperature_celsius');
            $table->timestamp('cooked_humidity_percent_updated_at')->nullable();
            $table->timestamp('cooked_temperature_celsius_updated_at')->nullable();
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
        Schema::dropIfExists('rooms');
    }
}
