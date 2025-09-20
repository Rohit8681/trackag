@extends('admin.layout.layout')

@section('content')
<main class="app-main">
   <!-- Page Header -->
   <div class="app-content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <h3 class="mb-0">Roles</h3>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">User Management</a></li>
                  <li class="breadcrumb-item active">Roles</li>
               </ol>
            </div>
         </div>
      </div>
   </div>

   <!-- Page Content -->
   <div class="app-content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card mb-4">
                  <div class="card-header">
                     <h3 class="card-title">Role Control Panel</h3>
                     @can('create_roles')
                     <a href="{{ route('roles.create') }}" class="btn btn-primary float-end" style="max-width: 150px;">
                        Add New Role
                     </a>
                     @endcan
                  </div>
                  <div class="card-body">
                     @if (session('success'))
                     <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <strong>Success: </strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                     @endif
                     {{-- @can('view_roles') --}}
                     <table id="roles-table" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                           <tr>
                              <th>#</th>
                              <th>Name</th>
                              <th>Permissions</th>
                              <th>Status</th>
                              <th>Created</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse ($roles as $role)
                           <tr>
                              <td>{{ $loop->iteration }}</td>
                              <td>{{ $role->name }}</td>
                              <td>
                                 <button type="button" class="btn btn-sm btn-outline-dark"
                                    data-bs-toggle="modal"
                                    data-bs-target="#permissionsModal{{ $role->id }}">
                                    View
                                 </button>
                              </td>
                              <td>
                                 @if ($role->is_active ?? true)
                                 <span class="badge bg-success">Active</span>
                                 @else
                                 <span class="badge bg-secondary">Inactive</span>
                                 @endif
                              </td>
                              <td>{{ $role->created_at->format('Y-m-d') }}</td>
                              <td>
                                 @can('view_roles')
                                 <a href="{{ route('roles.show', $role) }}" class="text-info me-2" title="View Role"><i class="fas fa-eye"></i></a>
                                 @endcan
                                 @can('edit_roles')
                                 <a href="{{ route('roles.edit', $role) }}" class="text-warning me-2" title="Edit Role"><i class="fas fa-edit"></i></a>
                                 @endcan
                                 @can('edit_roles')
                                 @if (isset($role->is_active))
                                 <form action="{{ route('roles.toggle', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                       class="btn btn-link p-0 me-2 {{ $role->is_active ? 'text-danger' : 'text-success' }}"
                                       title="{{ $role->is_active ? 'Deactivate' : 'Activate' }} Role">
                                       <i class="fas {{ $role->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                    </button>
                                 </form>
                                 @endif
                                 @endcan
                                 @can('delete_roles')
                                 <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-danger" title="Delete Role">
                                       <i class="fas fa-trash"></i>
                                    </button>
                                 </form>
                                 @endcan
                              </td>
                           </tr>

                           <!-- Permissions Modal -->
                           <div class="modal fade" id="permissionsModal{{ $role->id }}" tabindex="-1"
                              aria-labelledby="permissionsModalLabel{{ $role->id }}"
                              aria-hidden="true">
                              <div class="modal-dialog modal-dialog-scrollable">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="permissionsModalLabel{{ $role->id }}">
                                          Permissions for {{ $role->name }}
                                       </h5>
                                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                       @if ($role->permissions && count($role->permissions))
                                       <ul class="list-group">
                                          @foreach ($role->permissions as $permission)
                                          <li class="list-group-item">{{ $permission->name }}</li>
                                          @endforeach
                                       </ul>
                                       @else
                                       <p class="text-muted">No permissions assigned.</p>
                                       @endif
                                    </div>
                                 </div>
                              </div>
                           </div>

                           @empty
                           <tr>
                              <td colspan="6" class="text-center text-muted">No roles found.</td>
                           </tr>
                           @endforelse
                        </tbody>
                     </table>
                     {{-- @endcan --}}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>
@endsection
