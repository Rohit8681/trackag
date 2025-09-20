@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!-- Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Create Role</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Form -->
        <div class="app-content">
            <div class="container-fluid">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Role Name -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mt-3">Assign Permissions</h5>
                            <hr>
                        </div>

                        <!-- Select All Checkbox -->
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="select-all">
                                <label class="form-check-label fw-bold" for="select-all">Select All Permissions</label>
                            </div>
                        </div>

                        <!-- User Permissions -->
                        <div class="col-md-3">
                            <h6>User Related</h6>
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'user')) as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Role Permissions -->
                        <div class="col-md-3">
                            <h6>Role Related</h6>
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'role')) as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Customer Permissions -->
                        <div class="col-md-3">
                            <h6>Customer Related</h6>
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'customer')) as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Company Permissions -->
                        <div class="col-md-3">
                            <h6>Company Related</h6>
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'companies')) as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Trip Permissions -->
                        <div class="col-md-3 mt-4">
                            <h6>Trip Related</h6>
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'trip')) as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Permissions Related Permissions -->
                        <div class="col-md-3 mt-4">
                            <h6>Permissions Related</h6>
                            @foreach ($permissions->filter(fn($p) => str_contains($p->name, 'permission')) as $permission)
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        class="form-check-input permission-checkbox" id="perm_{{ $permission->id }}">
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Role</button>
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
