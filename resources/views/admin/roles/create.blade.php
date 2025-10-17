@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold text-primary mb-0">Create Role</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="breadcrumb-item active">Create Role</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- Role Create Form --}}
                    <form method="POST" action="{{ route('roles.store') }}">
                        @csrf

                        {{-- Role Name --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Role Name</label>
                                <input type="text" class="form-control form-control-lg border-primary-subtle shadow-sm"
                                       id="name" name="name" placeholder="Enter role name" required>
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
                                            'User Related' => 'users',
                                            'Role Related' => 'roles',
                                            // 'Customer Related' => 'customer',
                                            'Company Related' => 'companies',
                                            // 'Trip Related' => 'trip',
                                            'Permission Related' => 'permissions',
                                        ];

                                        $actions = ['create','view', 'edit', 'delete', 'approve', 'reject', 'verify', 'dispatch', 'remove_review'];
                                    @endphp

                                    @foreach ($modules as $moduleName => $keyword)
                                        <tr>
                                            <td class="fw-semibold text-start">{{ $moduleName }}</td>
                                            @foreach ($actions as $action)
                                                
                                                @php
                                                    $permission = $permissions->firstWhere('name', "{$action}_{$keyword}");
                                                @endphp
                                                <td>
                                                    @if ($permission)
                                                        
                                                        <input type="checkbox" class="form-check-input permission-checkbox"
                                                            name="permissions[]" value="{{ $permission->name }}"
                                                            id="perm_{{ $permission->id }}">
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
                                <i class="fas fa-save me-2"></i>Create Role
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
