@extends('admin.layout.layout')
@section('title', 'Trip Types List | Trackag')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Trip Types List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Trip Types</a></li>
                            <li class="breadcrumb-item active">Trip Types</li>
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
                                <h3 class="card-title">Trip Types</h3>
                                {{-- <a href="{{ route('tourtype.create') }}" class="btn btn-sm btn-primary ms-auto">Add Trip Type</a> --}}
                            </div>
                            <div class="card-body table-responsive">
                                <table id="trips-table" class="table table-bordered table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tourtypes as $key => $type)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $type->name }}</td>
                                                <td>
                                                    <a href="{{ route('tourtype.edit', $type->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>

                                                    <form action="{{ route('tourtype.destroy', $type->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No
                                                    Trip types found.</td>
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
    </main>
@endsection