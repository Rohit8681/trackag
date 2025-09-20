@extends('admin.layout.layout')

@section('content')

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <h3>Permission Details</h3>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <h4>Name: {{ $permission->name }}</h4>
          <p>Guard Name: {{ $permission->guard_name }}</p>
          <p>Created At: {{ $permission->created_at->format('Y-m-d') }}</p>

          <a href="{{ route('permissions.index') }}" class="btn btn-secondary mt-3">Back to Permissions</a>
        </div>
      </div>
    </div>
  </div>
</main>

@endsection
