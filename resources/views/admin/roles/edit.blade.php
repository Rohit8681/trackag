@extends('admin.layout.layout')
@section('title', 'Edit Role | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Role</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Edit Role</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
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
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="create">
                                            Create
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="view">
                                            View
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="edit">
                                            Edit
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="delete">
                                            Delete
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="approve">
                                            Approve
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="reject">
                                            Reject
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="verify">
                                            Verify
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="dispatch">
                                            Dispatch
                                        </th>
                                        <th>
                                            <input type="checkbox" class="form-check-input column-select" data-column="remove_review">
                                            Remove Review
                                        </th>
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

                                        $modules = array_merge($modules, [
                                            'All Trips' => 'all_trip',
                                            'Trip Types' => 'trip_types',
                                            'Travel Modes' => 'travel_modes',
                                            'Trip Purposes' => 'trip_purposes',
                                            'Designations' => 'designations',
                                            'Attendance' => 'attendance',
                                            'States' => 'states',
                                            'Districts' => 'districts',
                                            'Talukas' => 'talukas',
                                            'Vehicle Types' => 'vehicle_types',
                                            'Depo Master' => 'depo_master',
                                            'Holiday Master' => 'holiday_master',
                                            'Leave Master' => 'leave_master',
                                            'TA-DA' => 'ta_da',
                                            'TA-DA Bill Master' => 'ta_da_bill_master',
                                            'Party (Customer)' => 'customers',
                                        ]);

                                        if (auth()->user() && auth()->user()->hasRole('master_admin')) {
                                            $modules['Companies'] = 'companies';
                                        }

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
                                                            data-action="{{ $action }}"
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

{{-- Select All + Column Select Script --}}
<script>
    // Select all permissions
    document.getElementById('select-all').addEventListener('change', function (event) {
        const isChecked = event.target.checked;
        document.querySelectorAll('.permission-checkbox, .column-select').forEach(cb => cb.checked = isChecked);
    });

    // Column-wise select/deselect
    document.querySelectorAll('.column-select').forEach(headerCb => {
        headerCb.addEventListener('change', function () {
            const column = this.getAttribute('data-column');
            document.querySelectorAll(`.permission-checkbox[data-action="${column}"]`).forEach(cb => cb.checked = this.checked);
        });
    });

    // Auto update column header when all checkboxes under it are selected
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const column = this.getAttribute('data-action');
            const allBoxes = document.querySelectorAll(`.permission-checkbox[data-action="${column}"]`);
            const headerBox = document.querySelector(`.column-select[data-column="${column}"]`);
            if (headerBox) {
                headerBox.checked = Array.from(allBoxes).every(b => b.checked);
            }

            // Also update the main select-all if everything is checked
            const allPermissions = document.querySelectorAll('.permission-checkbox');
            const selectAll = document.getElementById('select-all');
            selectAll.checked = Array.from(allPermissions).every(b => b.checked);
        });
    });
</script>

@endsection
