@extends('admin.layout.layout')

@section('content')

<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Admin Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Admin Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card card-primary card-outline mb-4">

                        {{-- Card Header --}}
                        <div class="card-header">
                            <h5 class="card-title mb-0">Admin Details</h5>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body">

                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile</th>
                                        <td>{{ $user->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td><!-- Admin Type here --></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <!--
                                                Replace with:
                                                <span class="badge bg-success">Active</span>
                                                or
                                                <span class="badge bg-danger">Inactive</span>
                                            -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Image</th>
                                        <td>
                                            <!--
                                                <img src="path/to/image.jpg" alt="Admin Image" class="img-thumbnail" style="width: 100px; height: auto;">
                                                or
                                                <span class="text-muted">No image uploaded</span>
                                            -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $user->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $user->updated_at }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        {{-- Card Footer --}}
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Edit Admin
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</main>

@endsection
