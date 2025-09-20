<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        // Fetch state_id dynamically by name
        $stateIds = DB::table('states')->pluck('id', 'name'); 
        // Example: ['Maharashtra' => 5, 'Karnataka' => 6, ...]

        $districts = [
            ['name' => 'Pune', 'state' => 'Maharashtra'],
            ['name' => 'Mumbai', 'state' => 'Maharashtra'],
            ['name' => 'Bangalore', 'state' => 'Karnataka'],
            ['name' => 'Ahmedabad', 'state' => 'Gujarat'],
            ['name' => 'Chennai', 'state' => 'Tamil Nadu'],
            ['name' => 'Nagpur', 'state' => 'Maharashtra'],
        ];

        foreach ($districts as $district) {
            $stateId = $stateIds[$district['state']] ?? null;

            if ($stateId) {
                DB::table('districts')->updateOrInsert(
                    ['name' => $district['name'], 'state_id' => $stateId],
                    ['state_id' => $stateId]
                );
            }
        }
    }
}
