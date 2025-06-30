<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade'); // Link to a business
            $table->string('transactionable_type');
            $table->unsignedBigInteger('transactionable_id');
            $table->index(['transactionable_type', 'transactionable_id']); // For polymorphic relations

            $table->enum('type', ['payment', 'expense', 'refund']);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->enum('status', ['completed', 'pending', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // e.g., 'Bank Transfer', 'Cash', 'Credit Card'
            $table->string('reference_number')->nullable()->unique();
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
        Schema::dropIfExists('property_transactions');
    }
}
