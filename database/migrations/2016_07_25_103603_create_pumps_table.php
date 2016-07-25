<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePumpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumps', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('controlunit_id')->nullable();
            #$table->foreign('controlunit_id')->references('id')->on('controlunits');
            $table->string('name');
            $table->string('state')->default('unknown');
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
        Schema::drop('pumps');
    }
}
