<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_name');
            $table->string('vehicle_number')->unique();
            $table->enum('vehicle_type', ['Petrol', 'Diesel', 'CNG', 'EV', 'LPG']);
            $table->unsignedBigInteger('assigned_person')->nullable();
            $table->decimal('milage', 8, 2)->nullable(); // per Ltr/KG
            $table->date('assign_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Foreign key relation
            $table->foreign('assigned_person')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
