<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSensorreadingUpdatedAtColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terraria', function (Blueprint $table) {
            $table->timestamp('cooked_humidity_percent_updated_at')->nullable();
            $table->timestamp('cooked_temperature_celsius_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terraria', function (Blueprint $table) {
            $table->dropColumn('cooked_humidity_percent_updated_at');
            $table->dropColumn('cooked_temperature_celsius_updated_at');
        });
    }
}
