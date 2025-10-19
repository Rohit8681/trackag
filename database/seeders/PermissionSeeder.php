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
            'delete_budget_plan',
            'view_monthly_plan',
            'create_monthly_plan',
            'edit_monthly_plan',
            'delete_monthly_plan',
            'view_plan_vs_achievement',
            'create_plan_vs_achievement',
            'edit_plan_vs_achievement',
            'delete_plan_vs_achievement',
            'view_party_visit',
            'create_party_visit',
            'edit_party_visit',
            'delete_party_visit',
            'view_order',
            'create_order',
            'edit_order',
            'delete_order',
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
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                ['guard_name' => 'web']
            );
        }
    }
}
