<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('action_sequence_id')->nullable();
            $table->string('target_type');
            $table->uuid('target_id');
            $table->string('desired_state');
            $table->integer('duration_minutes');
            $table->integer('sequence_sort_id');
            $table->uuid('wait_for_started_action_id')->nullable();
            $table->uuid('wait_for_finished_action_id')->nullable();
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
        Schema::drop('actions');
    }
}
