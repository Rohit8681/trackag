<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('state_id')->constrained('states')->cascadeOnDelete();
            $table->boolean('is_rpl')->default(0);
            $table->boolean('is_ncr')->default(0);
            $table->boolean('is_advance')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->unique(['product_id', 'state_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_state');
    }
};
