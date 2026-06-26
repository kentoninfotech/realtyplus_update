<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->foreignId('payment_plan_id')->constrained('payment_plans')->onDelete('cascade');
            
            $table->integer('installment_number'); // 1st, 2nd, 3rd installment etc.
            $table->decimal('amount_due', 15, 2); // Amount for this installment
            $table->decimal('amount_paid', 15, 2)->default(0);
            
            $table->date('due_date'); // When payment is due
            $table->date('paid_date')->nullable(); // When it was actually paid
            $table->integer('days_overdue')->default(0); // Calculated field
            
            $table->string('status')->default('pending'); // pending, paid, overdue, partial, waived
            $table->string('payment_method')->nullable(); // Bank Transfer, Cash, Credit Card, Cheque, Mobile Money
            $table->string('reference_number')->nullable(); // Transaction reference
            
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
        Schema::dropIfExists('payment_installments');
    }
}
