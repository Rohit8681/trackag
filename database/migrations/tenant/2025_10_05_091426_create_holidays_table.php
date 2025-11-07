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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->json('state_ids')->nullable();
            $table->date('holiday_date');
            $table->string('holiday_name');
            $table->string('holiday_type');
            $table->enum('is_paid', ['Yes', 'No'])->default('Yes');
            $table->boolean('status')->default(1);
            $table->timestamps();

            // $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};