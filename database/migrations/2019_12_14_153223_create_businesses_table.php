<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('business_name',70)->nullable();
            $table->string('motto',70)->nullable();
            $table->string('logo',70)->nullable();
            $table->string('address',100)->nullable();
            $table->string('background',70)->nullable();
            $table->string('primary_color')->nullable()->default('#0000FF');
            $table->string('secondary_color')->nullable()->default('#5e9a52');
            $table->string('mode',30)->nullable();
            $table->string('deployment_type', 30)->nullable();
            // $table->unsignedBigInteger('businessgroup_id')->index()->nullable();
            // $table->foreign('businessgroup_id')->references('id')->on('businessgroups')->nullable();
            $table->timestamps();
        });

        // DB::table('businesses')->insert(
        //     array(
        //         'user_id' => 1, // Assuming the first user is the owner
        //         'business_name' => 'RealtyPlus HQ',
        //         'motto' => 'Your Home, Our Priority',
        //         'logo' => 'realtyplus-logo.png',
        //         'address' => '123 Main Street, City, Country',
        //         'background' => 'office.jpg',
        //         'primary_color' => '#0000FF',
        //         'secondary_color' => '#5e9a52',
        //         'mode' => 'production',
        //         'deployment_type' => 'cloud',
        //     )
        // );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
