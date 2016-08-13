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
            #$table->foreign('terrarium_id')->references('id')->on('terraria');
            $table->string('lat_name')->nullable();
            $table->string('common_name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->boolean('notifications_enabled')->default(false);
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
