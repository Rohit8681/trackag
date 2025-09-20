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
        Schema::create('customer_trip', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('trip_id');
    $table->unsignedBigInteger('customer_id');
    $table->timestamps();

    $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_trip');
    }
};
