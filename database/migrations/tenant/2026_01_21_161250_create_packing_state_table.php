<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packing_states', function (Blueprint $table) {
            $table->id();

            $table->foreignId('packing_id')
                ->constrained('product_packings')
                ->cascadeOnDelete();

            $table->foreignId('state_id')
                ->constrained('states')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['packing_id', 'state_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_state');
    }
};
