@extends('admin.layout.layout')
@section('title', 'Create Brochure | Trackag')

@section('content')
<main class="app-main">
    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Brochure</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('brochure.index') }}">Brochure Master</a>
                        </li>
                        <li class="breadcrumb-item active">Add Brochure</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Add New Brochure</h3>
            </div>

            <div class="card-body">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Form --}}
                <form method="POST"
                      action="{{ route('brochure.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- State --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                State <span class="text-danger">*</span>
                            </label>
                            <select name="state_id" class="form-select" required>
                                <option value="">-- Select State --</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PDF Upload --}}
                        <div class="col-md-4">
                            <label class="form-label">
                                Upload PDF <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   name="pdf"
                                   class="form-control"
                                   accept="application/pdf"
                                   required>
                            @error('pdf')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <a href="{{ route('brochure.index') }}"
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
