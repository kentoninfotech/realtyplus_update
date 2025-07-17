<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('unit_number'); // e.g., 'Apt 101', 'Suite 300'
            $table->string('unit_type')->nullable(); // e.g., 'Studio', '1 Bed', 'Retail', 'Office'
            $table->text('description')->nullable(); // Additional details about the unit
            $table->decimal('square_footage', 10, 2)->nullable();
            $table->decimal('area_sqm', 15, 2)->nullable();
            $table->string('zoning_type')->nullable();
            $table->string('status')->default('available'); // e.g., 'vacant', 'sold', 'leased', 'under_maintenance', 'unavailable'
            $table->integer('floor_number')->nullable(); // e.g., 3
            $table->integer('bedrooms')->nullable();
            $table->decimal('bathrooms', 3, 1)->nullable(); // e.g., 2.5 bathrooms
            $table->decimal('sale_price', 15, 2)->nullable(); // Sale price if unit is for sale
            $table->decimal('rent_price', 15, 2)->nullable(); // Monthly rent if unit is for rent
            $table->decimal('deposit_amount', 15, 2)->nullable(); // e.g., 1500.00
            $table->date('available_from')->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'unit_number']); // A unit number should be unique within a property
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_units');
    }
}
