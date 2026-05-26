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
        Schema::table('product_packings', function (Blueprint $table) {
            $table->decimal('packing_value', 10, 2)->change();
            $table->decimal('unit_in_shipper', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_packings', function (Blueprint $table) {
            $table->integer('packing_value')->change();
            $table->integer('unit_in_shipper')->change();
        });
    }
};
