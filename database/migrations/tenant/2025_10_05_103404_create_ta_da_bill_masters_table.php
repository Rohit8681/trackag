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
        Schema::create('ta_da_bill_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('designation_id');
            $table->integer('day_limit')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta_da_bill_masters');
    }
};