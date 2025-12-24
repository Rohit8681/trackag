<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('party_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('customer_id');
            $table->string('payment_mode');
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->longText('remark')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('pending');
            $table->date('clear_return_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_payments');
    }
};
