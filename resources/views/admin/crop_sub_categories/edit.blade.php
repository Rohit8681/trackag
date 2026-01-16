@extends('admin.layout.layout')
@section('title', 'Edit Crop Sub Category | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit Crop Sub Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('crop-sub-categories.index') }}">Sub Categories</a>
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
                <h3 class="card-title">Edit Sub Category</h3>
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
                      action="{{ route('crop-sub-categories.update',$cropSubCategory->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Category --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>
                            <select name="crop_category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $cropSubCategory->crop_category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sub Category --}}
                        <div class="mb-3 col-md-6">
                            <label class="form-label">
                                Sub Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name',$cropSubCategory->name) }}"
                                   required>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="{{ route('crop-sub-categories.index') }}"
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
