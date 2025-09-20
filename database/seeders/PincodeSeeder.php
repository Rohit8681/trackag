<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PincodeSeeder extends Seeder
{
    public function run()
    {
        

        DB::table('pincodes')->insert([
            ['pincode' => '411028', 'city_id' => 1],
            ['pincode' => '400053', 'city_id' => 2],
            ['pincode' => '560066', 'city_id' => 3],
            ['pincode' => '400077', 'city_id' => 4],
            ['pincode' => '411001', 'city_id' => 5],
            ['pincode' => '560034', 'city_id' => 6],
            ['pincode' => '380008', 'city_id' => 7],
            ['pincode' => '600017', 'city_id' => 8],
            ['pincode' => '600053', 'city_id' => 9],
            ['pincode' => '440010', 'city_id' => 10],
        ]);
    }
}
