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
                            <table class="table table-bordered align-middle text-nowrap">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th style="width: 25%">Module</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $modules = [
                                            'User Related' => 'user',
                                            'Role Related' => 'role',
                                            'Customer Related' => 'customer',
                                            'Company Related' => 'companies',
                                            'Trip Related' => 'trip',
                                            'Permission Related' => 'permission',
                                        ];
                                    @endphp

                                    @foreach ($modules as $moduleName => $keyword)
                                        @php
                                            $modulePermissions = $permissions->filter(fn($p) => str_contains($p->name, $keyword));
                                        @endphp

                                        <tr>
                                            <td class="fw-bold text-secondary">{{ $moduleName }}</td>
                                            <td>
                                                @if($modulePermissions->isNotEmpty())
                                                    <table class="table table-borderless mb-0">
                                                        <tr>
                                                            @foreach($modulePermissions as $permission)
                                                                <td class="p-2">
                                                                    <div class="form-check">
                                                                        <input type="checkbox"
                                                                            class="form-check-input permission-checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->name }}"
                                                                            id="perm_{{ $permission->id }}">
                                                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                            {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                @if(($loop->iteration % 4) == 0)
                                                                    </tr><tr>
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                @else
                                                    <span class="text-muted fst-italic">No permissions found</span>
                                                @endif
                                            </td>
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
