@extends('admin.layout.layout')
@section('title', 'Travel Modes List | Trackag')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Travel Modes List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('travelmode.index') }}">Travel Modes</a></li>
                            <li class="breadcrumb-item active">Travel Modes</li>
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
                                <h3 class="card-title">Travel Modes</h3>
                                {{-- <a href="{{ route('travelmode.create') }}" class="btn btn-sm btn-primary ms-auto">Add Travel
                                    Modes</a> --}}
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
                                        @forelse ($travelModes as $key => $mode)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $mode->name }}</td>
                                                <td>
                                                    <a href="{{ route('travelmode.edit', $mode->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>

                                                    <form action="{{ route('travelmode.destroy', $mode->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No
                                                    travel modes found.</td>
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