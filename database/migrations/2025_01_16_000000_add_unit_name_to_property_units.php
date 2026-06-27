<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitNameToPropertyUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_units', function (Blueprint $table) {
            if (!Schema::hasColumn('property_units', 'unit_name')) {
                $table->string('unit_name')->nullable()->after('unit_number');
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
            if (Schema::hasColumn('property_units', 'unit_name')) {
                $table->dropColumn('unit_name');
            }
        });
    }
}
