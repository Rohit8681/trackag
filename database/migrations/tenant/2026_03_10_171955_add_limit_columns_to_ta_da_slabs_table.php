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
        Schema::table('ta_da_slabs', function (Blueprint $table) {
            $table->boolean('travel_mode_enabled')->default(0)->after('approved_bills_in_da_slab_wise');
            $table->decimal('travel_mode_limit', 10, 2)->nullable()->after('travel_mode_enabled');
            $table->boolean('tour_type_enabled')->default(0)->after('travel_mode_limit');
            $table->decimal('tour_type_limit', 10, 2)->nullable()->after('tour_type_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ta_da_slabs', function (Blueprint $table) {
            $table->dropColumn([
                'travel_mode_enabled',
                'travel_mode_limit',
                'tour_type_enabled',
                'tour_type_limit'
            ]);
        });
    }
};
