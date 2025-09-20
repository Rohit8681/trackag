@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0">Dashboard</h3>
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">

                {{-- Stats Summary --}}
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalUsers }}</h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="{{ url('admin/users') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totalRoles }}</h3>
                                <p>Total Roles</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <a href="{{ url('admin/roles') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalPermissions }}</h3>
                                <p>Total Permissions</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <a href="{{ url('admin/permissions') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <h2>{{ $databaseName ?? "" }}</h2>

                    @if (!is_null($totalCustomers))
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $totalCustomers }}</h3>
                                    <p>Total Customers</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <a href="{{ url('admin/customers') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Online Users --}}
                <div class="card card-primary card-outline mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Online Users Details</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Roles</th>
                                    <th>Permissions</th>
                                    <th>Last Seen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($onlineUsers as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->mobile ?? 'N/A' }}</td>
                                        <td>
                                            @foreach ($user->getRoleNames() as $role)
                                                <span class="badge bg-success">{{ $role }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#permissionsModal-{{ $user->id }}">
                                                View
                                            </button>

                                            {{-- Permissions Modal --}}
                                            <div class="modal fade" id="permissionsModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Permissions for {{ $user->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if (count($user->getAllPermissionsList()))
                                                                @foreach ($user->getAllPermissionsList() as $permission)
                                                                    <span class="badge bg-info mb-1">{{ $permission }}</span>
                                                                @endforeach
                                                            @else
                                                                <p>No permissions assigned.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->last_seen ? $user->last_seen->diffForHumans() : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users online currently.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Login & Logout History --}}
                <div class="card card-warning card-outline mt-4">
                    <div class="card-header">
                        <h3 class="card-title">User Login & Logout History</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>First Login</th>
                                    <th>Last Logout</th>
                                    <th>Total Session Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sessionsGrouped as $userId => $userSessions)
                                    @php
                                        $user = $userSessions->first()->user;
                                        $firstLogin = $userSessions->sortBy('login_at')->first()->login_at;
                                        $lastLogoutSession = $userSessions->whereNotNull('logout_at')->sortByDesc('logout_at')->first();
                                        $lastLogout = $lastLogoutSession ? $lastLogoutSession->logout_at : null;
                                        $totalDuration = $userSessions->sum('session_duration');
                                    @endphp
                                    <tr>
                                        <td>
                                            @if ($user)
                                                <a href="javascript:void(0);" class="view-sessions-link text-primary fw-bold" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                    {{ $user->name }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $user?->email ?? 'N/A' }}</td>
                                        <td>{{ $firstLogin ? $firstLogin->format('d-m-Y H:i:s') : 'N/A' }}</td>
                                        <td>{{ $lastLogout ? $lastLogout->format('d-m-Y H:i:s') : 'Active' }}</td>
                                        <td>{{ $totalDuration ? gmdate('H:i:s', $totalDuration) : 'Active' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No session records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Session History Modal --}}
                <div class="modal fade" id="sessionHistoryModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="sessionHistoryModalLabel">Session History</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="sessionHistoryContent">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
