<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            ['name' => 'Sales Executive', 'description' => 'Responsible for client visits and product demos'],
            ['name' => 'Area Sales Manager', 'description' => 'Manages a team of executives in a region'],
            ['name' => 'Regional Manager', 'description' => 'Supervises multiple areas under a region'],
            ['name' => 'Business Development Manager', 'description' => 'Focuses on expanding business opportunities'],
            ['name' => 'General Manager', 'description' => 'Overall operations management'],
        ];

        $companyId = 1; // 🔸 Set your target company_id here

        foreach ($designations as $designation) {
            Designation::create([
                'company_id'  => $companyId,
                'name'        => $designation['name'],
                'description' => $designation['description'],
            ]);
        }
    }
}
