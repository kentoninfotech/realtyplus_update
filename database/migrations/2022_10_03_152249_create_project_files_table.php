<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->index();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->unsignedBigInteger('milestone_id')->index();
            $table->foreign('milestone_id')->references('id')->on('project_milestones')->onDelete('cascade');
            $table->unsignedBigInteger('task_id')->index();
            $table->foreign('task_id')->nullable()->references('id')->on('tasks')->onDelete('cascade');
            $table->string('file_name',150)->nullable();
            $table->string('file_title')->nullable();
            $table->string('details',255)->nullable();
            $table->string('featured',5)->nullable();
            $table->string('cloud_location',30)->nullable();
            $table->foreignId('business_id')->nullable()->constrained('businesses')->onDelete('cascade');
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
        Schema::dropIfExists('project_files');
    }
}
