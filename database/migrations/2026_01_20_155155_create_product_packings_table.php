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
        Schema::create('product_packings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('packing_value', 10, 2);
            $table->enum('packing_size', ['GM','KG','ML','LTR','UNIT']);
            $table->enum('shipper_type', ['Bag','Box','Bucket','Drum']);
            $table->decimal('shipper_size', 10, 2);
            $table->decimal('unit_in_shipper', 10, 2);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packings');
    }
};
