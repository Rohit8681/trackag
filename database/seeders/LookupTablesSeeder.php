<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyId = 1; // 👈 default master admin company id — replace if needed

        DB::table('travel_modes')->insert([
            ['name' => 'Two Wheeler Personal',  'company_id' => $companyId],
            ['name' => 'Four Wheeler Personal',  'company_id' => $companyId],
            ['name' => 'Two Wheeler Company',  'company_id' => $companyId],
            ['name' => 'Four Wheeler Company',  'company_id' => $companyId],
            ['name' => 'Other',  'company_id' => $companyId],
            ['name' => 'Car',  'company_id' => $companyId],
            ['name' => 'Bike', 'company_id' => $companyId],
            ['name' => 'Walk', 'company_id' => $companyId],
        ]);

        DB::table('purposes')->insert([
            ['name' => 'Party Visit (Dealer/Payment)',     'company_id' => $companyId],
            ['name' => 'Field Visit',     'company_id' => $companyId],
            ['name' => 'Office Visit',     'company_id' => $companyId],
            ['name' => 'Work from home',     'company_id' => $companyId],
            ['name' => 'Other',     'company_id' => $companyId],
            ['name' => 'Official',     'company_id' => $companyId],
            ['name' => 'Client Visit', 'company_id' => $companyId],
            ['name' => 'Inspection',   'company_id' => $companyId],
        ]);

        DB::table('tour_types')->insert([
            ['name' => 'In Headquarter',        'company_id' => $companyId],
            ['name' => 'Out of Headquarter',        'company_id' => $companyId],
            ['name' => 'Tour with senior',        'company_id' => $companyId],
            ['name' => 'Work from home',        'company_id' => $companyId],
            ['name' => 'Farm Visit',        'company_id' => $companyId],
            ['name' => 'Local',        'company_id' => $companyId],
            ['name' => 'Outstation',   'company_id' => $companyId],
            ['name' => 'International', 'company_id' => $companyId],
        ]);
    }
}
