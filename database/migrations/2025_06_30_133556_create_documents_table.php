<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade'); // Optional business association
            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');
            $table->index(['documentable_type', 'documentable_id']); // For polymorphic relations

            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // e.g., 'pdf', 'docx', 'jpg'
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->onDelete('set null');
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
        Schema::dropIfExists('documents');
    }
}
