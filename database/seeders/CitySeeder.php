<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run()
    {
        DB::table('cities')->insert([
            ['name' => 'Hadapsar', 'district_id' => 1],
            ['name' => 'Andheri', 'district_id' => 2],
            ['name' => 'Whitefield', 'district_id' => 3],
            ['name' => 'Ghatkopar', 'district_id' => 2],
            ['name' => 'Koregaon Park', 'district_id' => 1],
            ['name' => 'Koramangala', 'district_id' => 3],
            ['name' => 'Maninagar', 'district_id' => 4],
            ['name' => 'T. Nagar', 'district_id' => 5],
            ['name' => 'Ambattur', 'district_id' => 5],
            ['name' => 'Dharampeth', 'district_id' => 6],
        ]);
    }
}
