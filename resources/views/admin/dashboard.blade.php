@extends('admin.layout.layout')

@push('styles')
    <style>
        .dashboard-page {
            background: #f4f6f9;
            min-height: calc(100vh - 57px);
        }

        .dashboard-header {
            padding: 1.25rem 0 0;
        }

        .dashboard-title {
            color: #1f2937;
            font-weight: 700;
            letter-spacing: 0;
        }

        .dashboard-subtitle {
            color: #6b7280;
            font-size: .95rem;
            margin-bottom: 0;
        }

        .dashboard-breadcrumb {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
            padding: .55rem .85rem;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            box-shadow: 0 12px 24px rgba(15, 23, 42, .06);
            display: flex;
            flex-direction: column;
            min-height: 148px;
            overflow: hidden;
            position: relative;
        }

        .metric-card::before {
            content: "";
            height: 4px;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
        }

        .metric-card.primary::before {
            background: #2563eb;
        }

        .metric-card.success::before {
            background: #16a34a;
        }

        .metric-card.warning::before {
            background: #f59e0b;
        }

        .metric-card.danger::before {
            background: #dc2626;
        }

        .metric-card-body {
            align-items: flex-start;
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            padding: 1.25rem 1.25rem 1rem;
        }

        .metric-label {
            color: #6b7280;
            font-size: .82rem;
            font-weight: 700;
            margin-bottom: .35rem;
            text-transform: uppercase;
        }

        .metric-value {
            color: #111827;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin: 0;
        }

        .metric-icon {
            align-items: center;
            border-radius: .5rem;
            display: inline-flex;
            flex: 0 0 48px;
            font-size: 1.35rem;
            height: 48px;
            justify-content: center;
            width: 48px;
        }

        .metric-icon.primary {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .metric-icon.success {
            background: #dcfce7;
            color: #15803d;
        }

        .metric-icon.warning {
            background: #fef3c7;
            color: #b45309;
        }

        .metric-icon.danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .metric-link {
            align-items: center;
            border-top: 1px solid #f1f5f9;
            color: #334155;
            display: flex;
            font-size: .9rem;
            font-weight: 700;
            gap: .45rem;
            justify-content: space-between;
            margin-top: auto;
            padding: .8rem 1.25rem;
            text-decoration: none;
        }

        .metric-link:hover {
            background: #f8fafc;
            color: #0f172a;
        }

        .database-pill {
            align-items: center;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: .5rem;
            color: #3730a3;
            display: inline-flex;
            font-size: .85rem;
            font-weight: 700;
            gap: .5rem;
            padding: .65rem .9rem;
        }

        .dashboard-card {
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            box-shadow: 0 12px 24px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .dashboard-card .card-header {
            align-items: center;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .dashboard-card .card-title {
            color: #111827;
            font-size: 1rem;
            font-weight: 800;
            margin: 0;
        }

        .online-count {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            border-radius: 999px;
            color: #166534;
            font-size: .8rem;
            font-weight: 700;
            padding: .3rem .65rem;
        }

        .dashboard-table {
            margin-bottom: 0;
        }

        .dashboard-table thead th {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            color: #475569;
            font-size: .78rem;
            letter-spacing: .02em;
            padding: .9rem 1rem;
            text-transform: uppercase;
            vertical-align: middle;
        }

        .dashboard-table tbody td {
            color: #334155;
            padding: 1rem;
            vertical-align: middle;
        }

        .user-cell {
            align-items: center;
            display: flex;
            gap: .75rem;
            min-width: 180px;
        }

        .user-avatar {
            align-items: center;
            background: #e0f2fe;
            border-radius: 50%;
            color: #0369a1;
            display: inline-flex;
            flex: 0 0 40px;
            font-weight: 800;
            height: 40px;
            justify-content: center;
            text-transform: uppercase;
            width: 40px;
        }

        .user-name {
            color: #111827;
            font-weight: 800;
            line-height: 1.2;
        }

        .user-email {
            color: #64748b;
            font-size: .85rem;
        }

        .badge-soft {
            border-radius: 999px;
            display: inline-flex;
            font-weight: 700;
            margin: .1rem;
            padding: .4rem .6rem;
        }

        .badge-soft-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-soft-info {
            background: #e0f2fe;
            color: #075985;
        }

        .empty-state {
            color: #64748b;
            padding: 2rem 1rem;
            text-align: center;
        }

        @media (max-width: 575.98px) {
            .dashboard-breadcrumb {
                margin-top: 1rem;
                width: 100%;
            }

            .metric-value {
                font-size: 1.65rem;
            }

            .dashboard-card .card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: .65rem;
            }
        }
    </style>
@endpush

@section('content')
    <main class="app-main dashboard-page">
        <div class="content-header dashboard-header">
            <div class="container-fluid">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4">
                    <div>
                        <h3 class="dashboard-title mb-1">Dashboard</h3>
                        <p class="dashboard-subtitle">Overview of users, access control and active sessions.</p>
                    </div>
                    <ol class="breadcrumb dashboard-breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content pb-4">
            <div class="container-fluid">

                {{-- Stats Summary --}}
                <div class="row g-3">
                    <div class="col-xl-3 col-md-6">
                        <div class="metric-card primary">
                            <div class="metric-card-body">
                                <div>
                                    <div class="metric-label">Total Users</div>
                                    <h3 class="metric-value">{{ $totalUsers }}</h3>
                                </div>
                                <span class="metric-icon primary">
                                    <i class="fas fa-users"></i>
                                </span>
                            </div>
                            <a href="{{ url('admin/users') }}" class="metric-link">
                                <span>View users</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    @if($isMasterAdmin == true)
                        <div class="col-xl-3 col-md-6">
                            <div class="metric-card success">
                                <div class="metric-card-body">
                                    <div>
                                        <div class="metric-label">Total Roles</div>
                                        <h3 class="metric-value">{{ $totalRoles }}</h3>
                                    </div>
                                    <span class="metric-icon success">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                </div>
                                <a href="{{ url('admin/roles') }}" class="metric-link">
                                    <span>Manage roles</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="metric-card warning">
                                <div class="metric-card-body">
                                    <div>
                                        <div class="metric-label">Total Permissions</div>
                                        <h3 class="metric-value">{{ $totalPermissions }}</h3>
                                    </div>
                                    <span class="metric-icon warning">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                                <a href="{{ url('admin/permissions') }}" class="metric-link">
                                    <span>Manage permissions</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        @if (!is_null($totalCustomers))
                            <div class="col-xl-3 col-md-6">
                                <div class="metric-card danger">
                                    <div class="metric-card-body">
                                        <div>
                                            <div class="metric-label">Total Customers</div>
                                            <h3 class="metric-value">{{ $totalCustomers }}</h3>
                                        </div>
                                        <span class="metric-icon danger">
                                            <i class="fas fa-user-friends"></i>
                                        </span>
                                    </div>
                                    <a href="{{ url('admin/customers') }}" class="metric-link">
                                        <span>View customers</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if (!empty($databaseName))
                            <div class="col-12">
                                <span class="database-pill">
                                    <i class="fas fa-database"></i>
                                    Database: {{ $databaseName }}
                                </span>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Online Users --}}
                <div class="card dashboard-card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Online Users Details</h3>
                        <span class="online-count">{{ $onlineUsers->count() }} Online</span>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap dashboard-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Roles</th>
                                    <th>Permissions</th>
                                    <th>Last Seen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($onlineUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                <span class="user-avatar">{{ substr($user->name, 0, 1) }}</span>
                                                <div>
                                                    <div class="user-name">{{ $user->name }}</div>
                                                    <div class="user-email">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->mobile ?? 'N/A' }}</td>
                                        <td>
                                            @forelse ($user->getRoleNames() as $role)
                                                <span class="badge-soft badge-soft-success">{{ $role }}</span>
                                            @empty
                                                <span class="text-muted">N/A</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#permissionsModal-{{ $user->id }}">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>

                                            {{-- Permissions Modal --}}
                                            <div class="modal fade" id="permissionsModal-{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Permissions for {{ $user->name }}</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if (count($user->getAllPermissionsList()))
                                                                @foreach ($user->getAllPermissionsList() as $permission)
                                                                    <span class="badge-soft badge-soft-info">{{ $permission }}</span>
                                                                @endforeach
                                                            @else
                                                                <p class="text-muted mb-0">No permissions assigned.</p>
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
                                        <td colspan="5" class="empty-state">No users online currently.</td>
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
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
