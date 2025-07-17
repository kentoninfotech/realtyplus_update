<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
            $table->foreignId('property_type_id')->constrained()->onDelete('restrict');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null'); // Agent who listed
            $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null'); // Owner of the property
            $table->string('name');
            $table->string('address');
            $table->string('state');
            $table->string('country')->default('Nigeria');
            $table->text('description')->nullable();
            $table->string('status')->default('available'); // e.g., 'vacant', 'sold', 'leased', 'under_maintenance', 'unavailable'
            $table->decimal('latitude', 10, 7)->nullable(); // For geo-location
            $table->decimal('longitude', 10, 7)->nullable(); // For geo-location
            $table->integer('area_sqft')->nullable();
            $table->integer('lot_size_sqft')->nullable();
            $table->year('year_built')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->decimal('rent_price', 15, 2)->nullable(); // For rental properties
            $table->date('date_acquired')->nullable();
            $table->string('listing_type')->default('for_sale'); // Options: for_sale, for_rent, sold
            $table->date('listed_at')->nullable();
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
        Schema::dropIfExists('properties');
    }
}
