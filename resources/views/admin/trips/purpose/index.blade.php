@extends('admin.layout.layout')
@section('title', 'Trip Purposes List | Trackag')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Trip Purposes List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('travelmode.index') }}">Trip Purposes</a></li>
                            <li class="breadcrumb-item active">Trip Purposes </li>
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
                                <h3 class="card-title">Trip Purposes</h3>
                                @can('create_trip_purposes')
                                {{-- <a href="{{ route('purpose.create') }}" class="btn btn-sm btn-primary ms-auto">Add Trip Purposes</a> --}}
                                @endcan
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
                                        @forelse ($purposes as $key => $purpose)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $purpose->name }}</td>
                                                <td>
                                                    @can('edit_trip_purposes')
                                                    <a href="{{ route('purpose.edit', $purpose->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                                    @endcan
                                                    

                                                    @can('delete_trip_purposes')
                                                    <form action="{{ route('purpose.destroy', $purpose->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                    @endcan

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ auth()->user()->user_level === 'master_admin' ? 4 : 3 }}">No
                                                    purposes found.</td>
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