@extends('admin.layout.layout')
@section('title', 'Edit Crop Category | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Crop Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('crop-categories.index') }}">Crop Categories</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Category</h3>
            </div>

            <div class="card-body">

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST"
                      action="{{ route('crop-categories.update',$cropCategory->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 col-md-6">
                        <label class="form-label">
                            Category Name <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name',$cropCategory->name) }}"
                               required>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="{{ route('crop-categories.index') }}"
                           class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>
@endsection
