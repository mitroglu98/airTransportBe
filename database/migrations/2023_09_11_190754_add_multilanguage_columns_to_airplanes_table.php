<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultilanguageColumnsToAirplanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airplanes', function (Blueprint $table) {
            $table->string('name_en')->after('name');  // Assuming 'name' exists already
            $table->string('name_cg')->after('name_en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('airplanes', function (Blueprint $table) {
            $table->dropColumn('name_en');
            $table->dropColumn('name_cg');
        });
    }
}
