<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 8)->default('NGN');
            $table->string('billing_cycle', 20)->default('monthly'); // monthly, quarterly, yearly, lifetime
            $table->integer('trial_days')->default(0);
            $table->integer('max_users')->nullable();
            $table->integer('max_properties')->nullable();
            $table->integer('max_personnel')->nullable();
            $table->json('features')->nullable(); // ["Unlimited leases","Email support",...]
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
