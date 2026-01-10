@extends('admin.layout.layout')
@section('title', 'Create Price List | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Price List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('price.index') }}">Price Master</a>
                        </li>
                        <li class="breadcrumb-item active">Add Price List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">

            <div class="card-header">
                <h3 class="card-title">Add New Price List</h3>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('price.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- State --}}
                        <div class="col-md-6">
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
                        </div>

                        {{-- PDF --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                Upload Price List (PDF) <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   name="pdf"
                                   class="form-control"
                                   accept="application/pdf"
                                   required>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            SAVE
                        </button>
                        <a href="{{ route('price.index') }}"
                           class="btn btn-secondary">
                            CANCEL
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</main>
@endsection
