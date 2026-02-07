<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripLogsTable extends Migration
{
    public function up()
    {
        Schema::create('trip_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('recorded_at')->nullable();
            $table->decimal('battery_percentage', 5, 2)->nullable();
            $table->boolean('gps_status')->default(true);
            $table->boolean('mobile_status')->default(true);
            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('trip_logs');
    }
}
