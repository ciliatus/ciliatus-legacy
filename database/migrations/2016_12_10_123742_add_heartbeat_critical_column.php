<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHeartbeatCriticalColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terraria', function (Blueprint $table) {
            $table->boolean('heartbeat_critical')->default(false);
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
            $table->dropColumn('heartbeat_critical');
        });
    }
}
