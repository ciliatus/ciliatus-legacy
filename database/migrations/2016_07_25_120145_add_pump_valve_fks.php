<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPumpValveFks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pumps', function (Blueprint $table) {
            #$table->foreign('valve_id')->references('id')->on('valves');
        });
        Schema::table('valves', function (Blueprint $table) {
            #$table->foreign('pump_id')->references('id')->on('pumps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pumps', function (Blueprint $table) {
            #$table->dropForeign('valves_pump_id_foreign');
        });
        Schema::table('valves', function (Blueprint $table) {
            #$table->dropForeign('pump_valve_id_foreign');
        });
    }
}
