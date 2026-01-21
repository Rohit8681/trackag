@extends('admin.layout.layout')
@section('title', 'Create Product Category | Trackag')

@section('content')
<main class="app-main">
    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Product Category</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('product-categories.index') }}">Product Category</a>
                        </li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Add New Product Category</h3>
            </div>

            <div class="card-body">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('product-categories.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Category Name --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name') }}"
                                   placeholder="Enter category name"
                                   required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mt-2">
                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       {{ old('status', 1) ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <a href="{{ route('product-categories.index') }}"
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
