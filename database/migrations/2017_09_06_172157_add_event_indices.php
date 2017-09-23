<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->index('type');
            $table->index('belongsTo_type');
            $table->index('belongsTo_id');
            $table->index('name');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('type');
            $table->dropIndex('belongsTo_type');
            $table->dropIndex('belongsTo_id');
            $table->dropIndex('name');
            $table->dropIndex('created_at');
        });
    }
}
