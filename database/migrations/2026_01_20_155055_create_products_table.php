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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('state_id')->nullable();

            $table->string('product_name');
            $table->string('technical_name')->nullable();
            $table->string('item_code')->nullable();
            $table->foreignId('product_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('shipper_gross_weight', 10, 2)->nullable();
            $table->enum('master_packing', ['Yes', 'No'])->default('No');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
