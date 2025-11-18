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
            $table->string('bill_title')->nullable();
            $table->date('bill_date');
            $table->string('bill_type')->nullable();
            $table->text('bill_details_description')->nullable();
            $table->string('travel_mode')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->string('approval_status')->default('Pending')->after('amount');
            $table->timestamps();
            $table->softDeletes();
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
