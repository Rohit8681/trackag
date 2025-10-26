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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
