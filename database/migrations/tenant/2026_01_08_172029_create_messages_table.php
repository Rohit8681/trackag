<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['all', 'individual']);
                  
            $table->unsignedBigInteger('state_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->longText('message');

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
