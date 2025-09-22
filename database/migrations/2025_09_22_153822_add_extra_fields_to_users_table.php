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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_mobile', 20)->nullable()->after('postal_address'); // adjust 'phone' if not exist
            $table->string('village', 100)->nullable()->after('company_mobile');
            $table->unsignedBigInteger('depo_id')->nullable()->after('village');

            $table->boolean('is_web_login_access')->default(1)->after('depo_id');

            $table->string('account_no', 30)->nullable()->after('is_web_login_access');
            $table->string('branch_name', 100)->nullable()->after('account_no');
            $table->string('ifsc_code', 20)->nullable()->after('branch_name');

            $table->string('pan_card_no', 20)->nullable()->after('ifsc_code');
            $table->string('aadhar_no', 20)->nullable()->after('pan_card_no');

            $table->string('driving_lic_no', 50)->nullable()->after('aadhar_no');
            $table->date('driving_expiry')->nullable()->after('driving_lic_no');

            $table->string('passport_no', 50)->nullable()->after('driving_expiry');
            $table->date('passport_expiry')->nullable()->after('passport_no');
            $table->json('cancel_cheque_photos')->nullable()->after('passport_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_mobile',
                'village',
                'depo_id',
                'is_web_login_access',
                'account_no',
                'branch_name',
                'ifsc_code',
                'pan_card_no',
                'aadhar_no',
                'driving_lic_no',
                'driving_expiry',
                'passport_no',
                'passport_expiry',
            ]);
        });
    }
};
