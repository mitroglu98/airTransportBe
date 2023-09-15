<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightPassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     public function up()
{
    Schema::create('flight_passengers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('flight_id');
        $table->unsignedBigInteger('passenger_id');
        // Add other columns as needed
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
        Schema::dropIfExists('flight_passengers');
    }
}
