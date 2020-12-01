<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEndpointBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endpoint_bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('endpointId');
            $table->unsignedBigInteger('userId');
            $table->date('from');
            $table->date('to');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('endpointId')->references('id')->on('endpoints');
            $table->foreign('userId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('endpoint_bookings');
    }
}
