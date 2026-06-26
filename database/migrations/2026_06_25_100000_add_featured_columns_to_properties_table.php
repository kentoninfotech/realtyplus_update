<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedColumnsToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'featured')) {
                $table->boolean('featured')->default(false)->after('listed_at');
            }
            if (!Schema::hasColumn('properties', 'featured_order')) {
                $table->integer('featured_order')->nullable()->after('featured');
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
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'featured')) {
                $table->dropColumn('featured');
            }
            if (Schema::hasColumn('properties', 'featured_order')) {
                $table->dropColumn('featured_order');
            }
        });
    }
}
