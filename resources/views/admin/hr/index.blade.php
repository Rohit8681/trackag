@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Designations</h3>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('designations.create') }}" class="btn btn-primary">Add New Designation</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="designation-table" class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    @if (auth()->user()->user_level === 'master_admin')
                                        <th>Company</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($designations as $key => $designation)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $designation->name }}</td>
                                        <td>{{ $designation->description }}</td>
                                        @if (auth()->user()->user_level === 'master_admin')
                                            <td>{{ $designation->company->name ?? '-' }}</td>
                                        @endif
                                        <td>
                                            <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('designations.destroy', $designation->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No designations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection