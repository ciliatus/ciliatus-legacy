<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameGenericComponentsGenericToCustomColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_components', function (Blueprint $table) {
            $table->renameColumn('generic_component_type_id', 'custom_component_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_components', function (Blueprint $table) {
            $table->renameColumn('custom_component_type_id', 'generic_component_type_id');
        });
    }
}
