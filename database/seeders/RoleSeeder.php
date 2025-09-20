<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Step 1: Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Step 2: Truncate pivot and role table
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        Role::truncate();

        // Step 3: Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Step 4: Clear Spatie cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Step 5: Create roles
        $roles = ['admin', 'manager', 'executive'];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role],
                ['guard_name' => 'web']
            );
        }
    }
}
