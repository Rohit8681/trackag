@extends('admin.layout.layout')

@section('content')

<main class="app-main">

    {{-- Page Header --}}
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

    {{-- Main Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Role Name --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ $role->name }}" required>
                        </div>
                    </div>
                </div>

                {{-- Permissions Section --}}
                <div class="row">
                    <div class="col-12">
                        <h5 class="mt-3">Assign Permissions</h5>
                        <hr>
                    </div>

                    {{-- Select All Checkbox --}}
                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="select-all">
                            <label class="form-check-label fw-bold" for="select-all">Select All Permissions</label>
                        </div>
                    </div>

                    {{-- User Related --}}
                    <div class="col-md-3">
                        <h6>User Related</h6>
                        @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'user')) as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                       id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Role Related --}}
                    <div class="col-md-3">
                        <h6>Role Related</h6>
                        @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'role')) as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                       id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Customer Related --}}
                    <div class="col-md-3">
                        <h6>Customer Related</h6>
                        @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'customer')) as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                       id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Company Related --}}
                    <div class="col-md-3">
                        <h6>Company Related</h6>
                        @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'companies')) as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                       id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Trip Related --}}
                    <div class="col-md-3 mt-4">
                        <h6>Trip Related</h6>
                        @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'trip')) as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                       id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Permissions Related --}}
                    <div class="col-md-3 mt-4">
                        <h6>Permissions Related</h6>
                        @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'permission')) as $permission)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox"
                                       name="permissions[{{ $permission->name }}]"
                                       value="{{ $permission->name }}"
                                       {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                       id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- Submit Buttons --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Role</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</main>

{{-- Select All Permissions Script --}}
<script>
    document.getElementById('select-all').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = event.target.checked;
        });
    });
</script>

@endsection
