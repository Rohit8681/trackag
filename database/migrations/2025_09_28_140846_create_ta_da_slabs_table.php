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
        // Main TA-DA slab master
        Schema::create('ta_da_slabs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['individual', 'slab_wise'])->default('individual');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('max_monthly_travel', ['yes', 'no'])->nullable();
            $table->integer('km')->nullable();
            $table->json('approved_bills_in_da')->nullable();
            $table->timestamps();
        });

        // Vehicle Slab (now uses travel_modes)
        Schema::create('ta_da_vehicle_slabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ta_da_slab_id')->constrained('ta_da_slabs')->onDelete('cascade');
            $table->foreignId('travel_mode_id')->constrained('travel_modes')->onDelete('cascade'); // ✅ changed from vehicle_types
            $table->decimal('travelling_allow_per_km', 8, 2)->nullable();
            $table->string('type')->nullable(); // 'individual' or 'slab_wise'
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('designation_id')->nullable();
            $table->timestamps();
        });

        // Tour Slab
        Schema::create('ta_da_tour_slabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ta_da_slab_id')->constrained('ta_da_slabs')->onDelete('cascade');
            $table->foreignId('tour_type_id')->constrained('tour_types')->onDelete('cascade');
            $table->decimal('da_amount', 8, 2)->nullable();
            $table->string('type')->nullable(); // 'individual' or 'slab_wise'
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('designation_id')->nullable();

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
