<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRawvalueToAdjustedValueColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logical_sensor_thresholds', function (Blueprint $table) {
            $table->renameColumn('rawvalue_lowerlimit', 'adjusted_value_lowerlimit');
            $table->renameColumn('rawvalue_upperlimit', 'adjusted_value_upperlimit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logical_sensor_thresholds', function (Blueprint $table) {
            $table->renameColumn('adjusted_value_lowerlimit', 'rawvalue_lowerlimit');
            $table->renameColumn('adjusted_value_upperlimit', 'rawvalue_upperlimit');
        });
    }
}
