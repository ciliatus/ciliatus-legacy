<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerrariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terraria', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('controlunit_id');
            $table->uuid('valve_id');
            $table->string('name');
            $table->string('friendly_name');
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
        Schema::drop('terraria');
    }
}
