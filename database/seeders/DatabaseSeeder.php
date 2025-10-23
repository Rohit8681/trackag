<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            StateSeeder::class,
            DistrictSeeder::class,
            CitySeeder::class,
            TehsilSeeder::class,
            PincodeSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            MultiCompanySeeder::class,
            LookupTablesSeeder::class,
            // CustomerSeeder::class,
            // TripSeeder::class,
            // TripWithLogsSeeder::class,
            DesignationSeeder::class,
        ]);
        
    }
}
