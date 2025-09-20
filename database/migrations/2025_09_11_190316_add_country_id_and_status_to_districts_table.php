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
        Schema::table('districts', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->after('id'); // Adjust position as needed
            $table->tinyInteger('status')->default(1)->after('country_id'); // 1=Active, 0=Inactive
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn(['country_id', 'status']);
        });
    }
};
