<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\User;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Define target company names
        $targetCompanies = ['TATA', 'AIRTEL', 'RELIENCE'];

        // Get only those companies that match the given names
        $companies = Company::whereIn('name', $targetCompanies)->get();

        if ($companies->isEmpty()) {
            $this->command->error('❌ No matching companies found (TATA, AIRTEL, RELIENCE). Please seed companies first.');
            return;
        }

        // Fetch all executive users
        $executives = User::where('user_level', 'executive')->get();

        if ($executives->isEmpty()) {
            $this->command->warn('⚠️ No executives found. Customers will be created without assigned executives.');
        }

        $counter = 1;

        foreach ($companies as $company) {
            for ($i = 1; $i <= 5; $i++) { // 5 customers per company
                DB::table('customers')->insert([
                    'name'       => $company->name . ' Customer ' . $i,
                    'email'      => strtolower($company->code) . '_customer' . $i . '@example.com',
                    'phone'      => '98765' . str_pad($counter, 5, '0', STR_PAD_LEFT),
                    'address'    => 'Address for ' . $company->name,
                    'company_id' => $company->id,
                    'user_id'    => $executives->isNotEmpty() ? $executives->random()->id : null,
                    'is_active'  => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $counter++;
            }
        }

        $this->command->info('✅ Dummy customers seeded for companies: ' . implode(', ', $targetCompanies));
    }
}
