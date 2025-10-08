<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            [
                'name' => 'India',
                'code' => 'IN',
            ],
        ];
        DB::table('countries')->insert($countries);
    }
}