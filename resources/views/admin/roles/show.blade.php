@extends('admin.layout.layout')
@section('title', 'View Role | Trackag')

@section('content')

<main class="app-main">
  
  <div class="app-content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <h3 class="mb-0">View Role</h3>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                  <li class="breadcrumb-item active">View Role</li>
               </ol>
            </div>
         </div>
      </div>
    </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h4>Name: {{ $role->name }}</h4>
          <p>Created At: {{ $role->created_at->format('Y-m-d') }}</p>

          <h5>Permissions:</h5>
          @if($role->permissions->count())
            <ul>
              @foreach($role->permissions as $permission)
                <li>{{ $permission->name }}</li>
              @endforeach
            </ul>
          @else
            <p>No permissions assigned.</p>
          @endif

          <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">Back to Roles</a>
        </div>
      </div>
    </div>
  </div>
</main>

@endsection
