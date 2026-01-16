<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('mobile_no', 15)->nullable();
            $table->string('mobile_no_2', 15)->nullable();

            $table->string('farmer_name')->nullable();
            $table->string('village')->nullable();

            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('taluka_id')->nullable();

            $table->unsignedBigInteger('crop_sowing_id')->nullable();

            $table->string('land_acr')->nullable();
            
            $table->string('irrigation_type')->nullable();

            $table->timestamps();

            // Foreign Keys (optional but recommended)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
