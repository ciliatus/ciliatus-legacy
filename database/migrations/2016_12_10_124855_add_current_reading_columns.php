<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrentReadingColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terraria', function (Blueprint $table) {
            $table->double('cooked_humidity_percent')->nullable();
            $table->double('cooked_temperature_celsius')->nullable();
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
            $table->dropColumn('cooked_humidity_percent');
            $table->dropColumn('cooked_temperature_celsius');
        });
    }
}
