<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_crop_sowings', function (Blueprint $table) {
            $table->id();
            $table->integer('farmer_id');
            $table->integer('crop_sowing_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_crop_sowings');
    }
};
