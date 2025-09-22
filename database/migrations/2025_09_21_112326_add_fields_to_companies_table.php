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
        Schema::table('companies', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('subscription_type');
            $table->date('validity_upto')->nullable()->after('start_date');
            $table->integer('user_assigned')->nullable()->after('validity_upto')->comment('Number of users assigned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'validity_upto', 'user_assigned']);
        });
    }
};
