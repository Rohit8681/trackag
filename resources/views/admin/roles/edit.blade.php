@extends('admin.layout.layout')
@section('title', 'Edit Role | Trackag')

@section('content')

<main class="app-main">
    {{-- Header --}}
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

                {{-- Edit Role Form (SAME UI AS CREATE ROLE) --}}
                <form method="POST" action="{{ route('roles.update', $role->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Role Name --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role Name</label>
                            <input type="text" name="name" value="{{ $role->name }}"
                                   class="form-control form-control-lg" required>
                        </div>
                    </div>

                    {{-- Select All --}}
                    <div class="mb-3">
                        <input type="checkbox" id="select-all">
                        <label for="select-all" class="fw-semibold text-primary ms-1">Select All Permissions</label>
                    </div>

                    {{-- Permission Table (Main → Sub → Permissions) --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-start">Module</th>
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
                                $permissionTree = [
                                    'Budget Plan' => [
                                        'Budget Plan' => 'budget_plan',
                                        'Monthly Plan' => 'monthly_plan',
                                        'Plan Vs Achievement' => 'plan_vs_achievement',
                                    ],
                                    'Party (Customer)' => [
                                        'Party Visit' => 'party_visit',
                                        'New Party' => 'customers',
                                        'Party Payment' => 'party_payment',
                                        'Party Performance' => 'party_performance',
                                        'Party Ledger' => 'party_ledger',
                                    ],
                                    'Order' => [
                                        'Order List' => 'order',
                                        'Order Report' => 'order_report',

                                    ],
                                    'Stock' => [
                                        'Stock' => 'stock',
                                    ],
                                    'Tracking' => [
                                        'Tracking' => 'tracking',
                                    ],
                                    'Attendance' => [
                                        'Attendance' => 'attendance',
                                    ],
                                    'Expense' => [
                                        'Expense' => 'expense',
                                    ],
                                    'User Management' => [
                                        'Manage User' => 'users',
                                        'Manage Role' => 'roles',
                                    ],
                                    'Trip Management' => [
                                        'All Trips' => 'all_trip',
                                        'Trip Types' => 'trip_types',
                                        'Travel Modes' => 'travel_modes',
                                        'Trip Purposes' => 'trip_purposes',
                                    ],
                                    'Masters' => [
                                        'Designations' => 'designations',
                                        'States' => 'states',
                                        'Districts' => 'districts',
                                        'Talukas' => 'talukas',
                                        'Vehicle Types' => 'vehicle_types',
                                        'Depo Master' => 'depo_master',
                                        'Holiday Master' => 'holiday_master',
                                        'Leave Master' => 'leave_master',
                                    ],
                                    'TA-DA' => [
                                        'TA-DA' => 'ta_da',
                                        'TA-DA Bill Master' => 'ta_da_bill_master',
                                    ],
                                ];

                                if(auth()->user() && auth()->user()->hasRole('master_admin')){
                                    $permissionTree['System'] = [
                                        'Permissions' => 'permissions',
                                        'Companies' => 'companies',
                                    ];
                                }

                                $actions = ['create','view','edit','delete','approve','reject','verify','dispatch','remove_review'];
                            @endphp

                            @foreach($permissionTree as $mainModule => $subModules)
                                <tr class="table-secondary">
                                    <td colspan="10" class="text-start fw-bold">{{ $mainModule }}</td>
                                </tr>

                                @foreach($subModules as $subName => $key)
                                    <tr>
                                        <td class="text-start ps-4">{{ $subName }}</td>
                                        @foreach($actions as $action)
                                            @php
                                                $permission = $permissions->firstWhere('name', $action.'_'.$key)
                                                    ?? $permissions->firstWhere('name', $key.'_'.$action);
                                            @endphp
                                            <td>
                                                @if($permission)
                                                    <input type="checkbox"
                                                           class="permission-checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
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
                        <button type="submit" class="btn btn-primary px-4">Update Role</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</main>

<script>
    document.getElementById('select-all').addEventListener('change', function () {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>

@endsection
