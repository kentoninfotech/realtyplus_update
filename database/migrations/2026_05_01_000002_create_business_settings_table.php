<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->onDelete('cascade');
            $table->string('key', 80);
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_settings');
    }
}
