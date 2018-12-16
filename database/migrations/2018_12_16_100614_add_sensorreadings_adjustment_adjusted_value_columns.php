<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSensorreadingsAdjustmentAdjustedValueColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sensorreadings', function (Blueprint $table) {
            $table->double('rawvalue_adjustment')->default(0);
            $table->double('adjusted_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sensorreadings', function (Blueprint $table) {
            $table->dropColumn('rawvalue_adjustment');
            $table->dropColumn('adjusted_value');
        });
    }
}
