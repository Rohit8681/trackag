<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = ['AndhraPradesh', 'Assam', 'Bihar', 'Chattisgarh', 'Goa','Gujarat','Haryana','HimachalPradesh','Jharkhand','Karnataka
    ','Kerala','MadhyaPradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Puducherry','Punjab','Rajasthan
    ','TamilNadu','Telangana','Tripura','Uttarakhand','UttarPradesh','WestBengal'];

        foreach ($states as $state) {
            DB::table('states')->updateOrInsert(
                ['name' => $state],              // condition (check if state exists)
                ['country_id' => 1, 'name' => $state] // values to insert/update
            );
        }
    }
}
