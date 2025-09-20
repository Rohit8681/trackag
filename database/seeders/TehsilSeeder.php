<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TehsilSeeder extends Seeder
{
    public function run()
    {
        DB::table('tehsils')->insert([
            ['name' => 'Hadapsar East', 'city_id' => 1],
            ['name' => 'Andheri West', 'city_id' => 2],
            ['name' => 'Whitefield South', 'city_id' => 3],
        ]);
    }
}
