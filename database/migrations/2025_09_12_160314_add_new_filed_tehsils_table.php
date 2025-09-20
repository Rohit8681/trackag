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
        Schema::table('tehsils', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable()->after('id');
            $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
            $table->unsignedBigInteger('district_id')->nullable()->after('state_id');
            $table->boolean('status')->default(1)->after('district_id');

            // Foreign Keys
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tehsils', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['state_id']);
            $table->dropForeign(['district_id']);

            $table->dropColumn(['country_id', 'state_id', 'district_id', 'status']);
        });
    }
};
