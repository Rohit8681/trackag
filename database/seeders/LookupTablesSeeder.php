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
        DB::table('travel_modes')->insert([
            ['name' => 'Two Wheeler Personal'],
            ['name' => 'Four Wheeler Personal'],
            ['name' => 'Two Wheeler Company'],
            ['name' => 'Four Wheeler Company'],
            ['name' => 'Travel Modes List'],
            ['name' => 'Bus/Train/Other']
            // ['name' => 'Car',],
            // ['name' => 'Bike'],
            // ['name' => 'Walk'],
        ]);

        DB::table('purposes')->insert([
            ['name' => 'Party Visit (Dealer/Payment)'],
            ['name' => 'Field Visit'],
            ['name' => 'Office Visit'],
            ['name' => 'Work from home'],
            ['name' => 'Other',   ],
            // ['name' => 'Official',   ],
            // ['name' => 'Client Visit'],
            // ['name' => 'Inspection', ],
        ]);

        DB::table('tour_types')->insert([
            ['name' => 'In Headquarter'],
            ['name' => 'Out of Headquarter'],
            ['name' => 'Tour with senior'],
            ['name' => 'Work from home'],
            // ['name' => 'Farm Visit'],
            // ['name' => 'Local'],
            // ['name' => 'Outstation'],
            // ['name' => 'International'],
        ]);
    }
}
