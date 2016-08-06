<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('belongsTo_type');
            $table->uuid('belongsTo_id');
            $table->uuid('user_id');
            $table->string('state');
            $table->string('mimetype');
            $table->string('name');
            $table->string('parent_path');
            $table->string('display_name');
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
        Schema::drop('files');
    }
}
