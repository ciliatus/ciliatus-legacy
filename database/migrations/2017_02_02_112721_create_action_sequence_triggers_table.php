<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionSequenceTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_sequence_triggers', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->uuid('action_sequence_id');
            $table->uuid('logical_sensor_id');
            $table->double('reference_value');
            $table->string('reference_value_comparison_type');
            $table->integer('reference_value_duration_threshold_minutes');
            $table->integer('minimum_timeout_minutes')->nullable();
            $table->time('timeframe_start')->nullable();
            $table->time('timeframe_end')->nullable();
            $table->timestamp('last_start_at')->nullable()->default(null);
            $table->timestamp('last_finished_at')->nullable()->default(null);
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
        Schema::drop('action_sequence_triggers');
    }
}
