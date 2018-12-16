<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLsRawvalueToAdjustedValueColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logical_sensors', function (Blueprint $table) {
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
        Schema::table('logical_sensors', function (Blueprint $table) {
            $table->renameColumn('rawvalue_lowerlimit', 'adjusted_value_lowerlimit');
            $table->renameColumn('rawvalue_upperlimit', 'adjusted_value_upperlimit');
        });
    }
}
