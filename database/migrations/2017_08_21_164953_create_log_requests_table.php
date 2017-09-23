<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_requests', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('endpoint');
            $table->index('endpoint');
            $table->string('protocol');
            $table->index('protocol');
            $table->string('remote_address');
            $table->index('remote_address');
            $table->string('user_agent');
            $table->index('user_agent');
            $table->string('referrer')->nullable();
            $table->string('method');
            $table->index('method');
            $table->integer('http_status');
            $table->index('http_status');
            $table->string('geo_iso_code')->nullable();
            $table->string('geo_country')->nullable();
            $table->string('geo_city')->nullable();
            $table->string('geo_postal_code')->nullable();
            $table->string('geo_lat')->nullable();
            $table->string('geo_lon')->nullable();
            $table->integer('duration_ms');
            $table->index('duration_ms');
            $table->timestamp('request_time');
            $table->index('request_time');
            $table->timestamps();
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
        Schema::dropIfExists('log_requests');
    }
}
