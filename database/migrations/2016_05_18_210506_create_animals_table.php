<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('terrarium_id')->nullable();
            $table->string('lat_name');
            $table->string('common_name');
            $table->string('display_name');
            $table->string('gender')->nullable();
            $table->date('birth_date');
            $table->date('death_date')->nullable();
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
        Schema::drop('animals');
    }
}
