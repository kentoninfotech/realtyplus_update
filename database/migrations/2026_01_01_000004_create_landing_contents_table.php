<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingContentsTable extends Migration
{
    public function up()
    {
        Schema::create('landing_contents', function (Blueprint $table) {
            $table->id();
            $table->string('section', 50); // hero_slide, feature, testimonial, faq, stat, footer_link, setting
            $table->string('key', 100)->nullable(); // for setting type, the key (e.g. site_title)
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->json('extra')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['section', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('landing_contents');
    }
}
