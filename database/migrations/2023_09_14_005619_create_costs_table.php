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
        Schema::table('costs', function (Blueprint $table) {
            $table->dropColumn(['fuel_cost', 'crew_cost', 'service_cost']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('description')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->dropColumn(['amount', 'description']);
            $table->decimal('fuel_cost', 10, 2);
            $table->decimal('crew_cost', 10, 2);
            $table->decimal('service_cost', 10, 2);
        });
    }
}
