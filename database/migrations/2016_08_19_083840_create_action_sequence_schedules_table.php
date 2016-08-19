<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionSequenceSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_sequence_schedules', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->uuid('action_sequence_id');
            $table->time('starts_at');
            $table->timestamp('last_start_at')->nullable()->default(null);
            $table->timestamp('last_finished_at')->nullable()->default(null);
            $table->uuid('terrarium_id');
            $table->boolean('runonce')->default(false);
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
        Schema::drop('action_sequence_schedules');
    }
}
