<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flight_id')->nullable();
            $table->string('description'); // Add this line for 'description'
            $table->decimal('fuel_cost', 10, 2);
            $table->decimal('crew_cost', 10, 2);
            $table->decimal('service_cost', 10, 2);
            $table->timestamps();
    
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costs');
    }
}
