<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('party_id');
            $table->enum('order_type', ['cash','debit']);

            $table->unsignedBigInteger('depo_id')->nullable();
            $table->string('delivery_place')->nullable();
            $table->string('preferred_transport')->nullable();
            $table->text('remark')->nullable();
            $table->text('remark2')->nullable();
            $table->string('lr_number')->nullable();
            $table->string('transport_name')->nullable();
            $table->string('destination')->nullable();
            $table->dateTime('dispatch_date')->nullable();

            $table->enum('status', ['pending','approved','rejected','hold','part_dispatched','dispatched'])
                ->default('pending');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('party_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
