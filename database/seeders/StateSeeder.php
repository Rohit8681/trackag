<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = ['Maharashtra', 'Karnataka', 'Gujarat', 'Tamil Nadu'];

        foreach ($states as $state) {
            DB::table('states')->updateOrInsert(
                ['name' => $state], // unique condition
                ['name' => $state]  // update values if already exist
            );
        }
    }
}
