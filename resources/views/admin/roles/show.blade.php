@extends('admin.layout.layout')

@section('content')

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <h3>Role Details</h3>
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
