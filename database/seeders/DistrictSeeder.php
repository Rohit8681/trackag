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
            ['country_id' => 1, 'name' => 'Pune', 'state' => 'Maharashtra'],
            ['country_id' => 1,'name' => 'Mumbai', 'state' => 'Maharashtra'],
            ['country_id' => 1,'name' => 'Bangalore', 'state' => 'Karnataka'],
            ['country_id' => 1,'name' => 'Ahmedabad', 'state' => 'Gujarat'],
            ['country_id' => 1,'name' => 'Chennai', 'state' => 'Tamil Nadu'],
            ['country_id' => 1,'name' => 'Nagpur', 'state' => 'Maharashtra'],
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
