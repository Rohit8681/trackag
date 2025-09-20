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
        //Previous Table
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('role')->nullable();
        //     $table->string('mobile', 20)->nullable();
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->boolean('is_active')->default(true);
        //     $table->string('image')->nullable();
        //     $table->rememberToken();
        //     $table->timestamps();
        // });


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Already present
            $table->string('role')->nullable(); // Already present
            $table->string('mobile', 20)->nullable(); // Already present
            $table->string('email')->unique(); // Already present
            $table->timestamp('email_verified_at')->nullable(); // Already present
            $table->string('password'); // Already present
            $table->boolean('is_active')->default(true); // Already present
            $table->string('image')->nullable(); // Already present
            $table->rememberToken(); // Already present
            $table->timestamps(); // Already present
            $table->timestamp('last_seen')->nullable();

            // Only missing fields from the first schema are added below:
            $table->string('user_type')->nullable();
            $table->string('user_code')->unique()->nullable();
            $table->string('headquarter')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('emergency_contact_no')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('designation_id')->nullable();
            $table->string('role_rights')->nullable();
            $table->string('reporting_to')->nullable();
            $table->boolean('is_self_sale')->default(false);
            $table->boolean('is_multi_day_start_end_allowed')->default(false);
            $table->boolean('is_allow_tracking')->default(true);

            // Address Section
            $table->text('address')->nullable();
            // $table->string('state')->nullable();
            // $table->string('district')->nullable();
            // $table->string('tehsil')->nullable();
            // $table->string('city')->nullable();
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->foreignId('tehsil_id')->nullable()->constrained('tehsils')->onDelete('set null');



            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            // $table->string('pincode')->nullable();
            $table->foreignId('pincode_id')->nullable()->constrained('pincodes')->onDelete('set null');

            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('cascade');
            // $table->enum('user_level', ['master_admin', 'company_admin', 'user'])->default('user');
            $table->enum('user_level', ['master_admin', 'company_admin', 'user', 'admin', 'executive'])->default('user');



            $table->string('depo')->nullable();
            $table->string('postal_address')->nullable();

            // Status (extra status, different from is_active)
            $table->string('status')->default('Active');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
