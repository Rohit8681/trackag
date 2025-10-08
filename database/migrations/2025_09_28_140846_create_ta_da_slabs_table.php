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
        Schema::create('ta_da_slabs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['individual', 'slab_wise'])->default('individual');
            $table->integer('designation')->nullable(); // Only for slab_wise type
            $table->enum('max_monthly_travel', ['yes', 'no'])->nullable();
            $table->integer('km')->nullable();
            $table->json('approved_bills_in_da')->nullable();
            $table->timestamps();
        });

        Schema::create('ta_da_vehicle_slabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ta_da_slab_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->onDelete('cascade');
            $table->decimal('travelling_allow_per_km', 8, 2)->nullable();
            $table->string('type')->nullable(); // New field
            $table->unsignedBigInteger('user_id')->nullable(); // New field
            $table->timestamps();
        });

        Schema::create('ta_da_tour_slabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ta_da_slab_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_type_id')->constrained('tour_types')->onDelete('cascade');
            $table->decimal('da_amount', 8, 2)->nullable();
            $table->string('type')->nullable(); // New field
            $table->unsignedBigInteger('user_id')->nullable(); // New field
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_da_tour_slabs');
        Schema::dropIfExists('ta_da_vehicle_slabs');
        Schema::dropIfExists('ta_da_slabs');
    }
};
