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

        $stateCodes = [
            'AP', 'AS', 'BR', 'CG', 'GA',
            'GJ', 'HR', 'HP', 'JH', 'KA',
            'KL', 'MP', 'MH', 'MN', 'ML',
            'MZ', 'NL', 'PY', 'PB', 'RJ',
            'TN', 'TS', 'TR', 'UK', 'UP', 'WB'
        ];

        foreach ($states as $index => $state) {
            DB::table('states')->updateOrInsert(
                ['name' => $state], // condition (check if state exists)
                [
                    'country_id' => 1,
                    'name' => $state,
                    'state_code' => $stateCodes[$index],
                ]
            );
        }

        // foreach ($states as $state) {
        //     DB::table('states')->updateOrInsert(
        //         ['name' => $state],              // condition (check if state exists)
        //         ['country_id' => 1, 'name' => $state] // values to insert/update
        //     );
        // }
    }
}
