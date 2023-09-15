<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
     $table->string('destination_from');
        $table->string('destination_to');
        $table->timestamp('departure_time');
        $table->timestamp('arrival_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // public function down()
    // {
    //     Schema::dropIfExists('flights');
    // }
    public function down()
{
    Schema::disableForeignKeyConstraints(); 
    
    // Remove the foreign key constraint
    Schema::table('crew_flight', function (Blueprint $table) {
        $table->dropForeign(['flight_id']);
    });
    
    Schema::dropIfExists('crew_flight');
    
    Schema::enableForeignKeyConstraints(); 
}

}
