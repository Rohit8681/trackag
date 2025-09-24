<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            // 🔸 Foreign key constraint
            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');

            // 🔸 Unique constraint: designation name should be unique per company
            $table->unique([ 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designations');
    }
};
