<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->date('trip_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->decimal('start_lat', 10, 7)->nullable();
            $table->decimal('start_lng', 10, 7)->nullable();
            $table->decimal('end_lat', 10, 7)->nullable();
            $table->decimal('end_lng', 10, 7)->nullable();
            $table->decimal('total_distance_km', 8, 2)->nullable();
            // $table->string('travel_mode');

            // $table->string('purpose')->nullable();

            // $table->string('tour_type')->nullable();

            $table->string('place_to_visit')->nullable();
            $table->string('closenote')->nullable();
            $table->string('starting_km')->nullable();
            $table->string('end_km')->nullable();
            $table->string('start_km_photo')->nullable();
            $table->string('end_km_photo')->nullable();

            $table->enum('status', ['pending', 'approved', 'denied', 'completed', 'started'])->default('pending');

            // ✅ Add approval_status field
            $table->enum('approval_status', ['pending', 'approved', 'denied'])->default('pending');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('approval_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();

            // ✅ You need to define these columns first
            $table->unsignedBigInteger('travel_mode')->nullable();
            $table->unsignedBigInteger('purpose')->nullable();
            $table->unsignedBigInteger('tour_type')->nullable();
            // ✅ Now it's safe to add foreign keys for the new columns
            $table->foreign('travel_mode')->references('id')->on('travel_modes')->nullOnDelete();
            $table->foreign('purpose')->references('id')->on('purposes')->nullOnDelete();
            $table->foreign('tour_type')->references('id')->on('tour_types')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
