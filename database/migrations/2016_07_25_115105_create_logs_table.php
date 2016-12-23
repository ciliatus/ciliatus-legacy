<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('source_type')->nullable();
            $table->uuid('source_id')->nullable();
            $table->string('target_type')->nullable();
            $table->uuid('target_id')->nullable();
            $table->string('associatedWith_type')->nullable();
            $table->uuid('associatedWith_id')->nullable();
            $table->string('action');
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
        Schema::drop('logs');
    }
}
