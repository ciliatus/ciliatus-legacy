<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTerrariaStateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terraria', function (Blueprint $table) {
            $table->boolean('humidity_critical')->default(false);
            $table->boolean('temperature_critical')->default(false);
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
            $table->removeColumn('humidity_critical');
            $table->removeColumn('temperature_critical');
        });
    }
}
