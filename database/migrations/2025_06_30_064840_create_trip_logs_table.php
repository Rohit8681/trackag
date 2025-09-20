<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripLogsTable extends Migration
{
    public function up()
    {
        Schema::create('trip_logs', function (Blueprint $table) {
    $table->id();

    // Foreign key to trips table
    $table->unsignedBigInteger('trip_id');

    // Location coordinates
    $table->decimal('latitude', 10, 7);
    $table->decimal('longitude', 10, 7);

    // Optional timestamp when location was recorded
    $table->timestamp('recorded_at')->nullable();

    // Battery percentage (e.g. 75.5%)
    $table->decimal('battery_percentage', 5, 2)->nullable();

    // GPS status (on/off)
    $table->boolean('gps_status')->default(true);

    // Laravel's created_at and updated_at
    $table->timestamps();

    // Foreign key constraint: when a trip is deleted, its logs are deleted too
    $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
});

    }

    public function down()
    {
        Schema::dropIfExists('trip_logs');
    }
}
