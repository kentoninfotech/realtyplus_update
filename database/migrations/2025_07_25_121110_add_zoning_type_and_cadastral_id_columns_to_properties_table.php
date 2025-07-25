<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZoningTypeAndCadastralIdColumnsToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('zoning_type', 100)->nullable()->after('name')->comment('e.g., R-1, C-2, Mixed-Use');
            $table->string('cadastral_id', 255)->nullable()->after('zoning_type')->comment('Unique legal identifier for land parcel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('zoning_type');
            $table->dropColumn('cadastral_id');
        });
    }
}
