<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Source type
            $table->enum('type', ['web', 'mobile'])->default('web');

            // Mobile fields
            $table->date('visit_date')->nullable();
            $table->string('sales_person_name')->nullable();

            // Common fields
            $table->string('agro_name')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('party_code')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('tehsil_id')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('gst_no')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('credit_limit')->nullable();
            $table->tinyInteger('depo_id')->nullable();
            $table->date('party_active_since')->nullable();
            $table->boolean('is_active')->default(true);

            // New fields for mobile
            $table->string('working_with')->nullable();
            $table->json('party_documents')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'hold'])->default('pending');
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
