<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostFlightTable extends Migration
{
    public function up()
    {
        Schema::create('cost_flight', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost_id');
            $table->unsignedBigInteger('flight_id');
            $table->timestamps();
    
            $table->foreign('cost_id')
                  ->references('id')
                  ->on('costs')
                  ->onDelete('cascade');
    
            $table->foreign('flight_id')
                  ->references('id')
                  ->on('flights')
                  ->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('cost_flight');
    }
    
}
