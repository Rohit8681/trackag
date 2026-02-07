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
        Schema::create('farm_visits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('farmer_id')->nullable();
            $table->integer('crop_id')->nullable();

            $table->string('crop_days')->nullable();
            $table->string('crop_sowing_land_area')->nullable();
            $table->string('crop_condition')->nullable();
            $table->string('pest_disease')->nullable();

            // store multiple images as JSON
            $table->json('images')->nullable();

            // single video
            $table->string('video')->nullable();

            $table->longText('remark')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->longText('agronomist_remark')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_visits');
    }
};
