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
                            @if($currentUsers < $maxUsers || auth()->user()->user_level === 'master_admin')
                            <a href="{{ route('users.create') }}" style="float: right;" class="btn btn-sm btn-primary">
                                <i class="fas fa-user-plus me-1"></i> Add New User
                            </a>
                            @endif
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="table-responsive" style="max-height: 600px;">
                                <table id="users-table" class="table table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Employee Name</th>
                                            <th>Address</th>
                                            <th>Other Info</th>
                                            <th>It Self Sale</th>
                                            <th>Salary</th>
                                            <th>TA/DA Info</th>
                                            <th>Depo Access</th>
                                            <th>State Access</th>
                                            <th>Status</th>
                                            <th>Reset Password</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            @php
                                                $loggedInUserId = Auth::id();
                                                $isOnline = $user->last_seen && \Carbon\Carbon::parse($user->last_seen)->gt(now()->subMinutes(5));
                                                $rowClass = $user->id === $loggedInUserId ? 'table-primary' : ($isOnline ? 'table-success' : (!$user->is_active ? 'table-secondary' : ''));
                                                $gender = strtolower($user->gender ?? '');
                                                $defaultImage = $gender === 'female' ? asset('admin/images/avatar-female.png') : asset('admin/images/avatar-male.png');
                                                $userImage = $user->image ? asset('storage/' . $user->image) : $defaultImage;
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $userImage }}" alt="avatar" class="rounded-circle me-2" width="40" height="40">
                                                        <div>
                                                            <strong>{{ $user->name }}</strong><br>
                                                            <small class="text-muted">{{ $user->mobile ?? '-' }}</small><br>
                                                            <span class="badge bg-secondary">{{ $user->designation->name ?? '-' }}</span>
                                                            @if ($user->id === $loggedInUserId)
                                                                <span class="badge bg-success ms-1">You</span><br>
                                                            @endif
                                                            <small class="text-muted">{{ $user->reportingManager->name ?? '-' }}</small><br>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small>{{ $user->state->name ?? '-' }}</small><br>
                                                    <small>{{ $user->district->name ?? '-' }}</small><br>
                                                    <small>{{ $user->tehsil->name ?? '-' }}</small><br>
                                                    <small>{{ $user->village ?? '-' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $user->joining_date ? \Carbon\Carbon::parse($user->joining_date)->format('d-m-Y') : '-' }}</small><br>
                                                    <small>{{ $user->headquarter ?? '-' }}</small><br>
                                                    @if ($user->roles && count($user->roles))
                                                        @foreach ($user->getRoleNames() as $role)
                                                            <span class="badge bg-info text-dark">{{ $role }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No Role</span>
                                                    @endif
                                                    <br>
                                                    <small>{{ $user->depos?->depo_name ?? '-' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $user->is_self_sale ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $user->is_self_sale ? 'Yes' : 'No' }}
                                                    </span>
                                                </td>
                                                <td class="text-center"><i class="fas fa-cog text-muted"></i></td>
                                                <td class="text-center"><i class="fas fa-cog text-muted"></i></td>
                                                <td class="text-center"><i class="fas fa-cog text-muted"></i></td>
                                                <td class="text-center"><i class="fas fa-cog text-muted"></i></td>
                                                <td class="text-center">
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
                                                <td class="text-center">
                                                    <i class="fas fa-cog text-primary reset-password" style="cursor:pointer;" data-user-id="{{ $user->id }}"></i>
                                                </td>   
                                                <td class="text-center">
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('users.toggle', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center text-muted">No users found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="resetPasswordModalLabel">Reset User Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="resetPasswordForm">
              @csrf
              <input type="hidden" name="user_id" id="modalUserId">
              <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="password" required>
              </div>
            </form>
            <div id="resetPasswordMessage"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="resetPasswordBtn">Reset Password</button>
          </div>
        </div>
      </div>
    </div>

</main>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
    // Open modal and set user id
    $('.reset-password').click(function() {
        let userId = $(this).data('user-id');
        $('#modalUserId').val(userId);
        $('#newPassword').val('');
        $('#resetPasswordMessage').html('');
        $('#resetPasswordModal').modal('show');
    });

    // Handle reset password AJAX
    $('#resetPasswordBtn').click(function() {
        let formData = $('#resetPasswordForm').serialize();

        $.ajax({
            url: "{{ route('users.reset-password') }}",
            method: "POST",
            data: formData,
            success: function(res) {
                $('#resetPasswordMessage').html('<div class="alert alert-success">Password reset successfully!</div>');
                setTimeout(function() {
                    $('#resetPasswordModal').modal('hide');
                }, 1500);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger">';
                $.each(errors, function(key, value) {
                    errorHtml += value + '<br>';
                });
                errorHtml += '</div>';
                $('#resetPasswordMessage').html(errorHtml);
            }
        });
    });
});
</script>
@endpush

