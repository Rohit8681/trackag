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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('bill_date');
            
            // Bill type (Petrol, Food, Accommodation, etc.)
            $table->json('bill_type')->nullable();
            
            $table->text('bill_details_description')->nullable();
            $table->unsignedBigInteger('travel_mode_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes(); // for deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
