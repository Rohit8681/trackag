<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Step 2: Truncate related pivot tables first
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        // Step 3: Truncate permissions table
        Permission::truncate();

        // Step 4: Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Step 5: Clear Spatie cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Step 6: Define and insert permissions
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'toggle_users',
            'view_companies',
            'create_companies',
            'edit_companies',
            'delete_companies',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                ['guard_name' => 'web']
            );
        }
    }
}
