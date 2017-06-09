<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeControlunitIdFieldNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generic_components', function (Blueprint $table) {
            #$table->uuid('controlunit_id')->nullable()->change();
            DB::statement("ALTER TABLE generic_components CHANGE controlunit_id controlunit_id CHAR(36) DEFAULT NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generic_components', function (Blueprint $table) {
            //
        });
    }
}
