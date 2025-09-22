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

            if (!Schema::hasColumn('tehsils', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('id');
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            }

            if (!Schema::hasColumn('tehsils', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable()->after('country_id');
                $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            }

            if (!Schema::hasColumn('tehsils', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('state_id');
                $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            }

            if (!Schema::hasColumn('tehsils', 'status')) {
                $table->boolean('status')->default(1)->after('district_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tehsils', function (Blueprint $table) {
            if (Schema::hasColumn('tehsils', 'country_id')) {
                $table->dropForeign(['country_id']);
            }
            if (Schema::hasColumn('tehsils', 'state_id')) {
                $table->dropForeign(['state_id']);
            }
            if (Schema::hasColumn('tehsils', 'district_id')) {
                $table->dropForeign(['district_id']);
            }

            $columnsToDrop = [];
            foreach (['country_id', 'state_id', 'district_id', 'status'] as $col) {
                if (Schema::hasColumn('tehsils', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
