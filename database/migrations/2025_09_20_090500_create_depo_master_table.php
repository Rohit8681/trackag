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
        Schema::create('depos', function (Blueprint $table) {
            $table->id();
            $table->string('depo_code')->unique();
            $table->string('depo_name');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('tehsil_id')->nullable();
            $table->string('manage_by')->nullable();
            $table->string('city')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('tehsil_id')->references('id')->on('tehsils')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depos');
    }
};
