<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEndpoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endpoints', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('internalIp');
            $table->enum('type', ['rx','d5']);
            $table->uuid('credentialId');
            $table->uuid('gatewayId');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('credentialId')->references('id')->on('credentials');
            $table->foreign('gatewayId')->references('id')->on('gateways');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('endpoints');
    }
}
