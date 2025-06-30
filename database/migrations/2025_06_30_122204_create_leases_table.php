<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade'); // Optional business context
            $table->foreignId('property_id')->nullable()->constrained('properties')->onDelete('set null'); // Lease can be for a property or unit
            $table->foreignId('property_unit_id')->nullable()->constrained('property_units')->onDelete('set null');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade'); // Lease must have a tenant, delete tenant -> delete lease

            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 15, 2);
            $table->decimal('deposit_amount', 15, 2)->nullable();
            $table->string('payment_frequency', 20)->default('monthly'); // e.g., 'monthly', 'quarterly', 'annually'
            $table->date('renewal_date')->nullable(); // Date when lease can be renewed
            $table->string('status', 20)->default('pending'); // e.g., 'active', 'terminated', 'pending', 'expired'
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
        Schema::dropIfExists('leases');
    }
}
