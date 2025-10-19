@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold text-primary mb-0">Edit Role</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Edit Role</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- Role Edit Form --}}
                    <form method="POST" action="{{ route('roles.update', $role->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Role Name --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Role Name</label>
                                <input type="text"
                                       class="form-control form-control-lg border-primary-subtle shadow-sm"
                                       id="name" name="name"
                                       value="{{ $role->name }}" required>
                            </div>
                        </div>

                        {{-- Select All --}}
                        <div class="mb-3">
                            <input type="checkbox" id="select-all">
                            <label for="select-all" class="fw-semibold text-primary ms-1">Select All Permissions</label>
                        </div>

                        {{-- Permissions Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Module</th>
                                        <th>Create</th>
                                        <th>View</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Approve</th>
                                        <th>Reject</th>
                                        <th>Verify</th>
                                        <th>Dispatch</th>
                                        <th>Remove Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $modules = [
                                            'Budget Plan' => 'budget_plan',
                                            'Monthly Plan' => 'monthly_plan',
                                            'Plan Vs Achievement' => 'plan_vs_achievement',
                                            'Party Visit' => 'party_visit',
                                            'Order' => 'order',
                                            'Stock' => 'stock',
                                            'Tracking' => 'tracking',
                                            'Attendance' => 'attendance',
                                            'Expense' => 'expense',
                                            'Manage User' => 'users',
                                            'Manage Role' => 'roles',
                                            
                                        ];
                                        if (auth()->user() && auth()->user()->hasRole('master_admin')) {
                                            $modules['Manage Permissions'] = 'permissions';
                                        }
                                        $modules['All Trips'] = 'all_trip';
                                        $modules['Trip Types'] = 'trip_types';
                                        $modules['Travel Modes'] = 'travel_modes';
                                        $modules['Trip Purposes'] = 'trip_purposes';
                                        //$modules['Employees'] = 'employees';
                                        $modules['Designations'] = 'designations';
                                        $modules['Attendance'] = 'attendance';
                                        $modules['States'] = 'states';
                                        $modules['Districts'] = 'districts';
                                        $modules['Talukas'] = 'talukas';
                                        $modules['Vehicle Types'] = 'vehicle_types';
                                        // $modules['Vehicle Master'] = 'vehicle';
                                        $modules['Depo Master'] = 'depo_master';
                                        $modules['Holiday Master'] = 'holiday_master';
                                        $modules['Leave Master'] = 'leave_master';
                                        $modules['TA-DA'] = 'ta_da';
                                        $modules['TA-DA Bill Master'] = 'ta_da_bill_master';
                                        if (auth()->user() && auth()->user()->hasRole('master_admin')) {
                                            $modules['Companies'] = 'companies';
                                        }
                                        $modules['Party (Customer)'] = 'customers';

                                        $actions = ['create', 'view', 'edit', 'delete', 'approve', 'reject', 'verify', 'dispatch', 'remove_review'];
                                    @endphp

                                    @foreach ($modules as $moduleName => $keyword)
                                        <tr>
                                            <td class="fw-semibold text-start">{{ $moduleName }}</td>
                                            @foreach ($actions as $action)
                                                @php
                                                    // Match both naming patterns: "action_module" or "module_action"
                                                    $permission = $permissions->firstWhere('name', "{$action}_{$keyword}")
                                                        ?? $permissions->firstWhere('name', "{$keyword}_{$action}");
                                                @endphp
                                                <td>
                                                    @if ($permission)
                                                        <input type="checkbox"
                                                            class="form-check-input permission-checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            id="perm_{{ $permission->id }}"
                                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Buttons --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Update Role
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

{{-- Select All Script --}}
<script>
    document.getElementById('select-all').addEventListener('change', function (event) {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = event.target.checked);
    });
</script>

@endsection
