@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6"><h3>Edit Designation</h3></div>
                <div class="col-sm-6">
                    <a href="{{ route('designations.index') }}" class="btn btn-secondary float-end">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show">{{ $error }}</div>
                @endforeach
            @endif

            <div class="card">
                <form method="POST" action="{{ route('designations.update', $designation->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $designation->name) }}" required>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
