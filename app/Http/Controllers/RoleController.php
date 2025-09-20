<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        
        $user = Auth::user();
        // $roles = $user->user_level === 'master_admin'
        //     ? Role::all()
        //     : Role::where('company_id', $user->company_id)->get();

        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create', [
            'permissions' => Permission::all()
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'permissions' => 'array',
    ]);

    $user = Auth::user();
    $companyCode = $user->company->code ?? null;

    $roleName = $user->user_level === 'master_admin'
        ? $request->name
        : ($companyCode ? $companyCode . '_' . $request->name : $request->name);

    $role = Role::create([
        'name' => $roleName,
        'guard_name' => 'web',
        'company_id' => $user->user_level === 'master_admin' ? null : $user->company_id,
    ]);

    $role->syncPermissions($request->input('permissions', []));

    return redirect()->route('roles.index')->with('success', 'Role created successfully.');
}

    public function edit(string $id)
{
    $role = Role::findOrFail($id);
    $user = Auth::user();

    if ($user->user_level !== 'master_admin' && $role->company_id !== $user->company_id) {
        abort(403, 'Unauthorized access to role.');
    }

    // Remove company code prefix for non-master_admin
    $originalRoleName = $role->name;
    if ($user->user_level !== 'master_admin' && $user->company && $user->company->code) {
        $prefix = $user->company->code . '_';
        if (str_starts_with($role->name, $prefix)) {
            $originalRoleName = substr($role->name, strlen($prefix));
        }
    }

    // Overwrite name only for form display (not affecting the model itself)
    $role->name = $originalRoleName;

    return view('admin.roles.edit', [
        'role' => $role,
        'permissions' => Permission::all()
    ]);
}

    public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'permissions' => 'array',
    ]);

    $role = Role::findOrFail($id);
    $user = Auth::user();

    if ($user->user_level !== 'master_admin' && $role->company_id !== $user->company_id) {
        abort(403, 'Unauthorized update attempt.');
    }

    $companyCode = $user->company->code ?? null;

    $roleName = $user->user_level === 'master_admin'
        ? $request->name
        : ($companyCode ? $companyCode . '_' . $request->name : $request->name);

    $role->update(['name' => $roleName]);
    $role->syncPermissions($request->input('permissions', []));

    return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
}


    public function destroy(Role $role)
    {
        $user = Auth::user();

        if ($user->user_level !== 'master_admin' && $role->company_id !== $user->company_id) {
            abort(403, 'Unauthorized delete attempt.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function show(Role $role)
    {
        $user = Auth::user();

        if ($user->user_level !== 'master_admin' && $role->company_id !== $user->company_id) {
            abort(403, 'Unauthorized view attempt.');
        }

        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }
}
