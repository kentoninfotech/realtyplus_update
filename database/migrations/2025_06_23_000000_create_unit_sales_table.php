<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('property_unit_id')->constrained('property_units')->onDelete('cascade');
            
            // Polymorphic buyer (Owner, Tenant, or Client)
            $table->string('buyer_type'); // e.g., 'App\Models\Owner', 'App\Models\Tenant', 'App\Models\Client'
            $table->unsignedBigInteger('buyer_id');
            
            $table->decimal('sale_price', 15, 2);
            $table->date('sale_date')->nullable(); // Populated when sale is completed
            $table->enum('status', ['draft', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index(['business_id', 'property_unit_id']);
            $table->index(['buyer_type', 'buyer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_sales');
    }
}
