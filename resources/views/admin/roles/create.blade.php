@extends('admin.layout.layout')
@section('title', 'Create Role | Trackag')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Role</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Create Role</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        {{-- Role Name --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Role Name</label>
                                <input type="text" class="form-control form-control-lg"
                                       name="name" placeholder="Enter role name" required>
                            </div>
                        </div>

                        {{-- Select All --}}
                        <div class="mb-3">
                            <input type="checkbox" id="select-all">
                            <label for="select-all" class="fw-semibold text-primary ms-1">
                                Select All Permissions
                            </label>
                        </div>

                        {{-- PERMISSION TABLE --}}
                        <div class="table-responsive">
                            <table class="table permission-table table-bordered align-middle text-center">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Module</th>
                                        <th><input type="checkbox" class="column-select" data-column="create"> Create</th>
                                        <th><input type="checkbox" class="column-select" data-column="view"> View</th>
                                        <th><input type="checkbox" class="column-select" data-column="edit"> Edit</th>
                                        <th><input type="checkbox" class="column-select" data-column="delete"> Delete</th>
                                        <th><input type="checkbox" class="column-select" data-column="approvals"> Approve</th>
                                        <th><input type="checkbox" class="column-select" data-column="reject"> Reject</th>
                                        <th><input type="checkbox" class="column-select" data-column="verify"> Verify</th>
                                        <th><input type="checkbox" class="column-select" data-column="dispatch"> Dispatch</th>
                                        <th><input type="checkbox" class="column-select" data-column="remove_review"> Remove Review</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                    $actions = [
                                        'create' => 'Create',
                                        'view' => 'View',
                                        'edit' => 'Edit',
                                        'delete' => 'Delete',
                                        'approvals' => 'Approve',
                                        'reject' => 'Reject',
                                        'verify' => 'Verify',
                                        'dispatch' => 'Dispatch',
                                        'remove_review' => 'Remove Review',
                                    ];

                                    $matrix = [
                                        'Budget Plan' => [
                                            'budget_plan',
                                            'monthly_plan',
                                            'plan_vs_achievement',
                                        ],
                                        'Party (Customer)' => [
                                            'party_visit',
                                            'new_party',
                                            'party_payment',
                                            'party_performance',
                                            'party_ledger',
                                        ],
                                        'Order' => ['order','order_report'],
                                        'Stock' => ['stock'],
                                        'Tracking' => ['tracking'],
                                        'Attendance' => ['attendance'],
                                        'Expense' => ['expense'],
                                        'Trip Management' => [
                                            'all_trip',
                                            'trip_types',
                                            'travel_modes',
                                            'trip_purposes',
                                        ],
                                        'Masters' => [
                                            'designations',
                                            'states',
                                            'districts',
                                            'talukas',
                                            'vehicle_types',
                                            'depo_master',
                                            'holiday_master',
                                            'leave_master',
                                            'ta_da',
                                            'ta_da_bill_master',
                                        ],
                                        'User Management' => array_filter([
                                            'users',
                                            'roles',
                                            auth()->user() && auth()->user()->hasRole('master_admin')
                                                ? 'permissions'
                                                : null,
                                            'companies',
                                        ]),
                                    ];
                                    @endphp

                                    @foreach ($matrix as $mainModule => $subModules)

                                        {{-- MAIN MODULE --}}
                                        <tr style="background:#ffe600;" class="table-secondary">
                                            <td colspan="{{ count($actions) + 1 }}"
                                                class="fw-bold text-start">
                                                {{ $mainModule }}
                                            </td>
                                        </tr>

                                        {{-- SUB MODULE --}}
                                        @foreach ($subModules as $subModule)
                                            <tr>
                                                <td class="text-start ps-4 fw-semibold">
                                                    {{ ucwords(str_replace('_',' ', $subModule)) }}
                                                </td>

                                                {{-- @foreach ($actions as $actionKey => $label)
                                                    @php
                                                        $permissionName = $actionKey.'_'.$subModule;
                                                        $permission = $permissions->firstWhere('name', $permissionName);
                                                    @endphp
                                                    <td>
                                                        @if ($permission)
                                                            <input type="checkbox"
                                                                class="form-check-input permission-checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permissionName }}"
                                                                data-action="{{ $actionKey }}">
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                @endforeach --}}
                                                @foreach ($actions as $actionKey => $label)
                                                    @php
                                                        $permissionName = $actionKey.'_'.$subModule;
                                                        $permission = $permissions->firstWhere('name', $permissionName);

                                                        $isMasterAdmin = auth()->user() && auth()->user()->hasRole('master_admin');
                                                        $isCompanies   = ($subModule === 'companies');

                                                        if ($isMasterAdmin) {
                                                            $allowThisAction = true;
                                                        } else {
                                                            $allowThisAction = !$isCompanies || ($actionKey === 'view');
                                                        }
                                                    @endphp

                                                    <td>
                                                        @if ($permission && $allowThisAction)
                                                            <input type="checkbox"
                                                                class="form-check-input permission-checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permissionName }}"
                                                                data-action="{{ $actionKey }}">
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                @endforeach

                                            </tr>
                                        @endforeach

                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Buttons --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                Create Role
                            </button>
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</main>

{{-- JS --}}
<script>
document.getElementById('select-all').addEventListener('change', function () {
    document.querySelectorAll('.permission-checkbox, .column-select')
        .forEach(cb => cb.checked = this.checked);
});

document.querySelectorAll('.column-select').forEach(header => {
    header.addEventListener('change', function () {
        document.querySelectorAll(`.permission-checkbox[data-action="${this.dataset.column}"]`)
            .forEach(cb => cb.checked = this.checked);
    });
});
</script>
@endsection
