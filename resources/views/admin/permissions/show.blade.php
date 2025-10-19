@extends('admin.layout.layout')
@section('title', 'View Permission | Trackag')

@section('content')

<main class="app-main">
  <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">View Permission</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
                        <li class="breadcrumb-item active">View Permission</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

  <div class="app-content">
    <div class="container-fluid">
      <div class="card card-primary card-outline mb-4">
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
