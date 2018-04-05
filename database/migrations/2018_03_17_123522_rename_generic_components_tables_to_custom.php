<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameGenericComponentsTablesToCustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('generic_components', 'custom_components');
        Schema::rename('generic_component_types', 'custom_component_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('custom_components', 'generic_components');
        Schema::rename('custom_component_types', 'generic_component_types');
    }
}
