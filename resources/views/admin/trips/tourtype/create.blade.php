@extends('admin.layout.layout')
@section('title', 'Create Trip Types | Trackag')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Add Trip Types</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('tourtype.index') }}">Trip Types</a></li>
                            <li class="breadcrumb-item active">Add Trip Types</li>
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
                            <form method="POST" action="{{ route('tourtype.store') }}">
                                @csrf
                                <div class="card-body row g-3">

                                    <div class="col-md-12">
                                        <label class="form-label">Trip Types Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Create Trip Type</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection