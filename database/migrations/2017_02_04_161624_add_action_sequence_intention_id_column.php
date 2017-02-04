<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActionSequenceIntentionIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('running_actions', function (Blueprint $table) {
            $table->uuid('action_sequence_intention_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('running_actions', function (Blueprint $table) {
            $table->dropColumn('action_sequence_intention_id');
        });
    }
}
