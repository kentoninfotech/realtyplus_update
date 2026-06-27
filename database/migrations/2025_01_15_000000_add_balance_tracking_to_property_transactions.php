<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBalanceTrackingToPropertyTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_transactions', function (Blueprint $table) {
            // Add balance tracking fields
            $table->decimal('expected_amount', 15, 2)->nullable()->after('amount')->comment('Total amount expected (sale/rent price)');
            $table->decimal('balance_due', 15, 2)->nullable()->after('expected_amount')->comment('Outstanding balance');
            $table->boolean('is_partial_payment')->default(false)->after('balance_due')->comment('Whether this is a partial payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_transactions', function (Blueprint $table) {
            $table->dropColumn(['expected_amount', 'balance_due', 'is_partial_payment']);
        });
    }
}
