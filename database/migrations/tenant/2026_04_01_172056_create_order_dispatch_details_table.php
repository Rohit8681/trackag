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
        Schema::create('order_dispatch_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_dispatch_id');
            $table->string('lr_number')->nullable();
            $table->string('transport_name')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('dispatch_image')->nullable();
            $table->dateTime('dispatch_date')->nullable();
            
            $table->foreign('order_dispatch_id')->references('id')->on('order_dispatches')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_dispatch_details');
    }
};
