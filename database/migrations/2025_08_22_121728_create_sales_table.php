<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('property_unit_id')->nullable()->constrained('property_units')->onDelete('set null');

            $table->foreignId('buyer_id')->constrained('clients')->onDelete('cascade');

            $table->date('sale_date');
            $table->decimal('purchase_price', 15, 2);
            $table->string('status', 20)->default('pending'); // e.g., 'pending', 'closed', 'cancelled'
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
