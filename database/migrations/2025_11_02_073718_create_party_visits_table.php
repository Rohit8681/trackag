<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('party_visits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); 
            $table->integer('customer_id')->nullable();
            $table->date('visited_date')->nullable();
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();
            $table->integer('visit_purpose_id')->nullable();
            $table->date('followup_date')->nullable();
            $table->string('agro_visit_image')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_visits');
    }
};
