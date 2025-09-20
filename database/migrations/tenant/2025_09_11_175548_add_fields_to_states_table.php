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
         Schema::table('states', function (Blueprint $table) {
            // Add new columns
            if (!Schema::hasColumn('states', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('states', 'state_code')) {
                $table->string('state_code', 10)->nullable()->after('name');
            }
            if (!Schema::hasColumn('states', 'status')) {
                $table->boolean('status')->default(1)->after('state_code');
            }

            // Add foreign key
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('states', 'country_id')) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('states', 'state_code')) {
                $table->dropColumn('state_code');
            }
            if (Schema::hasColumn('states', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    
};
