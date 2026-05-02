<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BudgetPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_budget_plan',
            'create_budget_plan',
            'edit_budget_plan',
            'delete_budget_plan',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        // Assign to master_admin if exists
        $role = Role::where('name', 'master_admin')->first();
        if ($role) {
            $role->givePermissionTo($permissions);
        }
    }
}
