<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade'); // Optional business context
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null'); // Agent handling the lead
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('preferred_contact_method', ['email', 'phone', 'both'])->nullable();
            $table->string('source')->nullable(); // e.g., 'Website', 'Referral', 'Walk-in'
            $table->enum('status', ['new', 'contacted', 'qualified', 'viewing_scheduled', 'closed_won', 'closed_lost'])->default('new');
            $table->text('notes')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('property_type_interest')->nullable();
            $table->integer('bedrooms_interest')->nullable();
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
        Schema::dropIfExists('leads');
    }
}
