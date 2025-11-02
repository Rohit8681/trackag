<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('party_visits', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['daily', 'monthly'])->default('daily');
            $table->unsignedBigInteger('user_id');

            // Common Fields
            $table->string('employee_name')->nullable();

            // Daily Visit Fields
            $table->date('visited_date')->nullable();
            $table->string('agro_name')->nullable();
            $table->string('check_in_out_duration')->nullable();
            $table->text('visit_purpose')->nullable();
            $table->date('followup_date')->nullable();
            $table->string('agro_visit_image')->nullable();
            $table->text('remarks')->nullable();

            // Monthly Visit Fields
            $table->string('shop_name')->nullable();
            $table->integer('visit_count')->nullable(); // Total number of visits in month
            $table->string('last_visit_date')->nullable();
            $table->json('visit_purpose_count')->nullable(); // store JSON {"new_order":2,"payment_collection":3,...}

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_visits');
    }
};
