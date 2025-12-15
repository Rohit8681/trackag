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
            // 'delete_users',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            'view_customers',
            'create_customers',
            'edit_customers',
            'delete_customers',
            'toggle_customers',
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'toggle_users',
            'view_companies',
            'create_companies',
            'edit_companies',
            'delete_companies',
            'view_budget_plan',
            'create_budget_plan',
            'edit_budget_plan',
            // 'delete_budget_plan',
            'approvals_budget_plan',
            'reject_budget_plan',
            'verify_budget_plan',
            'remove_review_budget_plan',
            'view_monthly_plan',
            'create_monthly_plan',
            'edit_monthly_plan',
            // 'delete_monthly_plan',
            'approvals_monthly_plan',
            'reject_monthly_plan',
            'verify_monthly_plan',
            'remove_review_monthly_plan',
            'view_plan_vs_achievement',
            'create_plan_vs_achievement',
            'edit_plan_vs_achievement',
            // 'delete_plan_vs_achievement',
            'approvals_plan_vs_achievement',
            'reject_plan_vs_achievement',
            'verify_plan_vs_achievement',
            'remove_review_plan_vs_achievement',
            'view_party_visit',
            'approvals_party_visit',
            // 'create_party_visit',
            // 'edit_party_visit',
            // 'delete_party_visit',
            'view_order',
            // 'create_order',
            'edit_order',
            'delete_order',
            'approvals_order',
            'reject_order',
            'dispatch_order',
            'view_order_report',
            'view_stock',
            'create_stock',
            'edit_stock',
            'delete_stock',
            'view_tracking',
            'create_tracking',
            'edit_tracking',
            'delete_tracking',
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'delete_attendance',
            'view_expense',
            'create_expense',
            'edit_expense',
            'delete_expense',
            'view_all_trip',
            'create_all_trip',
            'edit_all_trip',
            'delete_all_trip',
            'approvals_all_trip',
            'logs_all_trip',
            'view_trip_types',
            'create_trip_types',
            'edit_trip_types',
            'delete_trip_types',
            'view_travel_modes',
            'create_travel_modes',
            'edit_travel_modes',
            'delete_travel_modes',
            'view_trip_purposes',
            'create_trip_purposes',
            'edit_trip_purposes',
            'delete_trip_purposes',
            'view_designations',
            'create_designations',
            'edit_designations',
            'delete_designations',
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'delete_attendance',
            'view_states',
            'create_states',
            'edit_states',
            'delete_states',
            'view_districts',
            'create_districts',
            'edit_districts',
            'delete_districts',
            'view_talukas',
            'create_talukas',
            'edit_talukas',
            'delete_talukas',
            'view_vehicle_types',
            'create_vehicle_types',
            'edit_vehicle_types',
            'delete_vehicle_types',
            'view_depo_master',
            'create_depo_master',
            'edit_depo_master',
            'delete_depo_master',
            'view_holiday_master',
            'create_holiday_master',
            'edit_holiday_master',
            'delete_holiday_master',
            'view_leave_master',
            'create_leave_master',
            'edit_leave_master',
            'delete_leave_master',
            'view_ta_da',
            'create_ta_da',
            'edit_ta_da',
            'delete_ta_da',
            'view_ta_da_bill_master',
            'create_ta_da_bill_master',
            'edit_ta_da_bill_master',
            'delete_ta_da_bill_master',
            'view_vehicle_master',
            'create_vehicle_master',
            'edit_vehicle_master',
            'delete_vehicle_master',
            'view_new_party',
            'approvals_new_party',
            'reject_new_party',
            'view_party_payment',
            'reject_party_payment',
            'approvals_party_payment',
            'view_party_performance',
            'view_party_ledger',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Step 4: Create shared roles
        // $subAdminRole = Role::firstOrCreate(['name' => 'sub_admin', 'guard_name' => 'web']);
        // $executiveRole = Role::firstOrCreate(['name' => 'executive', 'guard_name' => 'web']);

        // Assign appropriate permissions to the roles
        // $subAdminRole->syncPermissions([
        //     'view_users',
        //     'create_users',
        //     'edit_users',
        //     'delete_users',
        //     'view_roles',
        //     'create_roles',
        //     'edit_roles',
        //     'delete_roles',
        //     'view_permissions',
        //     'create_permissions',
        //     'edit_permissions',
        //     'delete_permissions',
        //     'view_customers',
        //     'create_customers',
        //     'edit_customers',
        //     'delete_customers',
        //     'toggle_customers',
        //     'view_products',
        //     'create_products',
        //     'edit_products',
        //     'delete_products',
        //     'toggle_users',
        //     'view_companies',
        //     'create_companies',
        //     'edit_companies',
        //     'delete_companies',
        //     'view_budget_plan',
        //     'create_budget_plan',
        //     'edit_budget_plan',
        //     'delete_budget_plan',
        //     'view_monthly_plan',
        //     'create_monthly_plan',
        //     'edit_monthly_plan',
        //     'delete_monthly_plan',
        //     'view_plan_vs_achievement',
        //     'create_plan_vs_achievement',
        //     'edit_plan_vs_achievement',
        //     'delete_plan_vs_achievement',
        //     'view_party_visit',
        //     'create_party_visit',
        //     'edit_party_visit',
        //     'delete_party_visit',
        //     'view_order',
        //     'create_order',
        //     'edit_order',
        //     'delete_order',
        //     'view_stock',
        //     'create_stock',
        //     'edit_stock',
        //     'delete_stock',
        //     'view_tracking',
        //     'create_tracking',
        //     'edit_tracking',
        //     'delete_tracking',
        //     'view_attendance',
        //     'create_attendance',
        //     'edit_attendance',
        //     'delete_attendance',
        //     'view_expense',
        //     'create_expense',
        //     'edit_expense',
        //     'delete_expense',
        //     'view_all_trip',
        //     'create_all_trip',
        //     'edit_all_trip',
        //     'delete_all_trip',
        //     'view_trip_types',
        //     'approvals_all_trip',
        //     'logs_all_trip',
        //     'create_trip_types',
        //     'edit_trip_types',
        //     'delete_trip_types',
        //     'view_travel_modes',
        //     'create_travel_modes',
        //     'edit_travel_modes',
        //     'delete_travel_modes',
        //     'view_trip_purposes',
        //     'create_trip_purposes',
        //     'edit_trip_purposes',
        //     'delete_trip_purposes',
        //     'view_designations',
        //     'create_designations',
        //     'edit_designations',
        //     'delete_designations',
        //     'view_attendance',
        //     'create_attendance',
        //     'edit_attendance',
        //     'delete_attendance',
        //     'view_states',
        //     'create_states',
        //     'edit_states',
        //     'delete_states',
        //     'view_districts',
        //     'create_districts',
        //     'edit_districts',
        //     'delete_districts',
        //     'view_talukas',
        //     'create_talukas',
        //     'edit_talukas',
        //     'delete_talukas',
        //     'view_vehicle_types',
        //     'create_vehicle_types',
        //     'edit_vehicle_types',
        //     'delete_vehicle_types',
        //     'view_depo_master',
        //     'create_depo_master',
        //     'edit_depo_master',
        //     'delete_depo_master',
        //     'view_holiday_master',
        //     'create_holiday_master',
        //     'edit_holiday_master',
        //     'delete_holiday_master',
        //     'view_holiday_master',
        //     'create_holiday_master',
        //     'edit_holiday_master',
        //     'delete_holiday_master',
        //     'view_leave_master',
        //     'create_leave_master',
        //     'edit_leave_master',
        //     'delete_leave_master',
        //     'view_ta_da',
        //     'create_ta_da',
        //     'edit_ta_da',
        //     'delete_ta_da',
        //     'view_ta_da_bill_master',
        //     'create_ta_da_bill_master',
        //     'edit_ta_da_bill_master',
        //     'delete_ta_da_bill_master',
        //     'view_vehicle_master',
        //     'create_vehicle_master',
        //     'edit_vehicle_master',
        //     'delete_vehicle_master',
        // ]);

        // $executiveRole->syncPermissions(['view_users']);

        // Step 5: Create companies and users
        // $companies = [
        //     ['name' => 'TATA', 'code' => 'TATA','subdomain' => 'TATA'],
        //     ['name' => 'AIRTEL', 'code' => 'AIRTEL','subdomain' => 'AIRTEL'],
        //     ['name' => 'RELIENCE', 'code' => 'RELIENCE','subdomain' => 'RELIENCE'],
        
        // ];

        // foreach ($companies as $companyData) {
        //     $company = Company::create($companyData);

        //     // Create sub-admin user for each company
        //     $admin = User::create([
        //         'name' => "Admin {$company->name}",
        //         'mobile' => '3333333333',
        //         'email' => "admin{$company->id}@example.com",
        //         'password' => bcrypt('password'),
        //         'company_id' => $company->id,
        //         'user_level' => 'admin',
        //         'is_active' => true,
        //     ]);
        //     $admin->assignRole($subAdminRole);

        //     // Create 2 executive users for each company
        //     for ($i = 1; $i <= 2; $i++) {
        //         $executive = User::create([
        //             'name' => "Executive{$i} {$company->name}",
        //             'mobile' => '2222222222',
        //             'email' => "executive{$i}_{$company->id}@example.com",
        //             'password' => bcrypt('password'),
        //             'company_id' => $company->id,
        //             'user_level' => 'executive',
        //             'is_active' => true,
        //         ]);
        //         $executive->assignRole($executiveRole);
        //     }
        // }

        // Step 6: Master Admin setup
        $masterAdminRole = Role::firstOrCreate(['name' => 'master_admin', 'guard_name' => 'web']);
        $masterAdminRole->syncPermissions(Permission::all());

        $masterAdmin = User::create([
            'name' => 'Master Admin',
            'mobile' => '1111111111',
            'email' => 'masteradmin@example.com',
            'password' => bcrypt('password'),
            'user_level' => 'master_admin',
            'is_active' => true,
        ]);

        $masterAdmin->assignRole($masterAdminRole);
    }
}
