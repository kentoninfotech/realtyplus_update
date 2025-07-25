<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropZoningTypeAndFloorNumberFromPropertyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_units', function (Blueprint $table) {
            if (Schema::hasColumns('property_units', ['zoning_type','floor_number'])){
                $table->dropColumn('zoning_type');
                $table->dropColumn('floor_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_units', function (Blueprint $table) {
            $table->string('zoning_type')->nullable()->after('status')->default('Residential');
            $table->integer('floor_number')->nullable()->after('zoning_type');
        });
    }
}
