<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenericComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generic_components', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('belongsTo_type');
            $table->uuid('belongsTo_id');
            $table->uuid('controlunit_id');
            $table->string('name');
            $table->uuid('generic_component_type_id');
            $table->string('state');
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
        Schema::drop('generic_components');
    }
}
