<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            
            // Polymorph support for both UnitSale and Lease
            $table->string('payable_type'); // e.g., 'App\Models\UnitSale', 'App\Models\Lease'
            $table->unsignedBigInteger('payable_id');
            $table->index(['payable_type', 'payable_id']);
            
            $table->string('payment_type'); // 'full' = single payment, 'installment' = multiple payments
            $table->decimal('total_amount', 15, 2); // Total sale/rent amount
            $table->decimal('amount_paid', 15, 2)->default(0); // Total amount paid so far
            $table->decimal('balance', 15, 2); // Remaining balance
            
            $table->string('status')->default('pending'); // pending, partial, completed, overdue, defaulted
            $table->integer('total_installments')->nullable(); // For installment plans
            $table->integer('installments_paid')->default(0);
            
            $table->date('start_date'); // When payment plan starts
            $table->date('end_date')->nullable(); // When payment plan should be completed
            $table->date('last_payment_date')->nullable();
            
            $table->text('notes')->nullable();
            
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
        Schema::dropIfExists('payment_plans');
    }
}
