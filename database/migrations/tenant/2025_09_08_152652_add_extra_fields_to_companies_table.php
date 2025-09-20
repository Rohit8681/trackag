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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('owner_name')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_no2')->nullable();
            $table->string('telephone_no')->nullable();
            $table->string('logo')->nullable(); // store path of logo file
            $table->string('website')->nullable();
            $table->string('state')->nullable();
            $table->string('product_name')->nullable();
            $table->string('subscription_type')->nullable();
            $table->boolean('tally_configuration')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'owner_name',
                'gst_number',
                'contact_no',
                'contact_no2',
                'telephone_no',
                'logo',
                'website',
                'state',
                'product_name',
                'subscription_type',
                'tally_configuration',
            ]);
        });
    }
};
