<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNextStartNotBeforeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('action_sequence_schedules', function (Blueprint $table) {
            $table->timestamp('next_start_not_before')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('action_sequence_schedules', function (Blueprint $table) {
            $table->dropColumn('next_start_not_before');
        });
    }
}
