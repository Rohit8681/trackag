<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class MultiCompanySeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Disable foreign key checks to prevent errors while truncating tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Step 2: Truncate relevant tables to reset the database state
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('users')->truncate();
        DB::table('companies')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Step 3: Define permissions
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'toggle_users',
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
            'view_companies',
            'create_companies',
            'edit_companies',
            'delete_companies',
            'toggle_companies',
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',
            'toggle_customers',
            'view_trips',
            'create_trips',
            'edit_trips',
            'delete_trips',
            'trip_approvals',
            'view_trip_logs'

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Step 4: Create shared roles
        $subAdminRole = Role::firstOrCreate(['name' => 'sub_admin', 'guard_name' => 'web']);
        $executiveRole = Role::firstOrCreate(['name' => 'executive', 'guard_name' => 'web']);

        // Assign appropriate permissions to the roles
        $subAdminRole->syncPermissions([
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'toggle_users',
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
            'view_companies',
            'create_companies',
            'edit_companies',
            'delete_companies',
            'toggle_companies',
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',
            'toggle_customers',
            'view_trips',
            'create_trips',
            'edit_trips',
            'delete_trips',
            'trip_approvals',
            'view_trip_logs'
        ]);

        $executiveRole->syncPermissions(['view_users']);

        // Step 5: Create companies and users
        $companies = [
            ['name' => 'TATA', 'code' => 'TATA','subdomain' => 'TATA'],
            ['name' => 'AIRTEL', 'code' => 'AIRTEL','subdomain' => 'AIRTEL'],
            ['name' => 'RELIENCE', 'code' => 'RELIENCE','subdomain' => 'RELIENCE'],
        
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            // Create sub-admin user for each company
            $admin = User::create([
                'name' => "Admin {$company->name}",
                'email' => "admin{$company->id}@example.com",
                'password' => bcrypt('password'),
                'company_id' => $company->id,
                'user_level' => 'admin',
                'is_active' => true,
            ]);
            $admin->assignRole($subAdminRole);

            // Create 2 executive users for each company
            for ($i = 1; $i <= 2; $i++) {
                $executive = User::create([
                    'name' => "Executive{$i} {$company->name}",
                    'email' => "executive{$i}_{$company->id}@example.com",
                    'password' => bcrypt('password'),
                    'company_id' => $company->id,
                    'user_level' => 'executive',
                    'is_active' => true,
                ]);
                $executive->assignRole($executiveRole);
            }
        }

        // Step 6: Master Admin setup
        $masterAdminRole = Role::firstOrCreate(['name' => 'master_admin', 'guard_name' => 'web']);
        $masterAdminRole->syncPermissions(Permission::all());

        $masterAdmin = User::create([
            'name' => 'Master Admin',
            'email' => 'masteradmin@example.com',
            'password' => bcrypt('password'),
            'user_level' => 'master_admin',
            'is_active' => true,
        ]);

        $masterAdmin->assignRole($masterAdminRole);
    }
}
