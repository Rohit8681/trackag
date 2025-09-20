@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Users</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">User Management</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">User Control Panel</h3>
                                {{-- @can('create_users') --}}
                                    <a href="{{ route('users.create') }}" style="float: right;" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user-plus me-1"></i> Add New User
                                    </a>
                                {{-- @endcan --}}
                            </div>

                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                {{-- @can('view_users') --}}
                                    @php $loggedInUserId = Auth::id(); @endphp
                                    <div class="table-responsive" style="max-height: 600px;">
                                        <table id="users-table"
                                            class="table table-bordered table-hover table-striped align-middle table-sm">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th>No</th>
                                                    <th>User ID</th>
                                                    <th>Img</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Company</th>
                                                    <th>Status</th>
                                                    <th>Online</th>
                                                    <th>Roles</th>
                                                    <th>Perms</th>
                                                    <th>Created</th>
                                                    <th title="User Code">Code</th>
                                                    <th>Mobile</th>
                                                    <th title="Designation">Desig.</th>
                                                    <th title="Reporting Manager">Report. To</th>
                                                    <th>HQ</th>
                                                    <th>State</th>
                                                    <th>District</th>
                                                    <th>Tehsil</th>
                                                    <th>City</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($users as $user)
                                                    @php
                                                        $isOnline = $user->last_seen && \Carbon\Carbon::parse($user->last_seen)->gt(now()->subMinutes(5));
                                                        $rowClass = $user->id === $loggedInUserId ? 'table-primary' : ($isOnline ? 'table-success' : (!$user->is_active ? 'table-secondary' : ''));
                                                        $gender = strtolower($user->gender ?? '');
                                                        $defaultImage = $gender === 'female' ? asset('admin/images/avatar-female.png') : asset('admin/images/avatar-male.png');
                                                        $userImage = $user->image ? asset('storage/' . $user->image) : $defaultImage;
                                                    @endphp
                                                    <tr class="{{ $rowClass }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $user->id }}</td>
                                                        <td>
                                                            <img src="{{ $userImage }}" alt="User Image" class="rounded-circle"
                                                                width="40" height="40" style="object-fit: cover;">
                                                        </td>
                                                        <td>
                                                            {{ $user->name }}
                                                            @if ($user->id === $loggedInUserId)
                                                                <span class="badge bg-success ms-1">You</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->company->name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($user->is_active)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check-circle me-1"></i> Active
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">
                                                                    <i class="fas fa-times-circle me-1"></i> Inactive
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $activePlatforms = $user->activeSessions
                                                                    ->whereNull('logout_at')
                                                                    ->whereIn('platform', ['web', 'mobile'])
                                                                    ->pluck('platform')
                                                                    ->unique();
                                                            @endphp

                                                            @if ($activePlatforms->isNotEmpty())
                                                                @foreach ($activePlatforms as $platform)
                                                                    <span class="badge {{ $platform == 'web' ? 'bg-primary' : 'bg-warning text-dark' }}">
                                                                        {{ ucfirst($platform) }}
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="badge bg-secondary">Offline</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($user->roles && count($user->roles))
                                                                @foreach ($user->getRoleNames() as $role)
                                                                    <span class="badge bg-info text-dark">{{ $role }}</span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted">None</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-outline-dark"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#permissionsModal{{ $user->id }}">
                                                                View
                                                            </button>
                                                        </td>
                                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                                        <td>{{ $user->user_code ?? '-' }}</td>
                                                        <td>{{ $user->mobile ?? '-' }}</td>
                                                        <td>{{ $user->designation->name ?? '-' }}</td>
                                                        <td>{{ $user->reportingManager->name ?? '-' }}</td>
                                                        <td>{{ $user->headquarter ?? '-' }}</td>
                                                        <td>{{ $user->state->name ?? '-' }}</td>
                                                        <td>{{ $user->district->name ?? '-' }}</td>
                                                        <td>{{ $user->tehsil->name ?? '-' }}</td>
                                                        <td>{{ $user->city->name ?? '-' }}</td>
                                                        <td>
                                                            {{-- @can('view_users') --}}
                                                                <a href="{{ route('users.show', $user) }}" class="text-info me-2" title="View User">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            {{-- @endcan --}}
                                                            {{-- @can('edit_users') --}}
                                                                <a href="{{ route('users.edit', $user) }}" class="text-warning me-2" title="Edit User">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            {{-- @endcan --}}
                                                            {{-- @can('toggle_users') --}}
                                                                <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-link p-0 me-2 {{ $user->is_active ? 'text-danger' : 'text-success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} User">
                                                                        <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                                    </button>
                                                                </form>
                                                            {{-- @endcan --}}
                                                            {{-- @can('delete_users') --}}
                                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this user?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-link p-0 text-danger" title="Delete User">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            {{-- @endcan --}}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="21" class="text-center text-muted">No users found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                {{-- @endcan --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Modals -->
        @foreach ($users as $user)
            <div class="modal fade" id="permissionsModal{{ $user->id }}" tabindex="-1"
                aria-labelledby="permissionsModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="permissionsModalLabel{{ $user->id }}">
                                Permissions for {{ $user->name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($user->roles->count())
                                @foreach ($user->roles as $role)
                                    <div class="mb-3">
                                        <strong>{{ $role->name }}</strong>
                                        @php $permissions = $role->permissions; @endphp
                                        @if ($permissions->count())
                                            <ul class="list-group mt-1">
                                                @foreach ($permissions as $permission)
                                                    <li class="list-group-item">
                                                        {{ $permission->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted">No permissions for this role.</p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">This user has no roles assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </main>
@endsection
