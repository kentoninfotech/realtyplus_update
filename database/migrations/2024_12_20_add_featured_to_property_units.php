<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedColumnsToUnitsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('property_units', function (Blueprint $table) {
            if (!Schema::hasColumn('property_units', 'featured')) {
                $table->boolean('featured')->default(false)->after('status')->index();
            }
            if (!Schema::hasColumn('property_units', 'featured_order')) {
                $table->integer('featured_order')->nullable()->after('featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_units', function (Blueprint $table) {
            if (Schema::hasColumn('property_units', 'featured')) {
                $table->dropColumn('featured');
            }
            if (Schema::hasColumn('property_units', 'featured_order')) {
                $table->dropColumn('featured_order');
            }
        });
    }
}
