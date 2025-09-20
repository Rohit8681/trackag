@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        {{-- Page Header --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">State Master</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Edit State</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="card-title mb-0">Edit State</div>
                            </div>

                            {{-- Flash Messages --}}
                            @if (Session::has('error_message'))
                                <div class="alert alert-danger alert-dismissible fade show m-3">
                                    <strong>Error:</strong> {{ Session::get('error_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (Session::has('success_message'))
                                <div class="alert alert-success alert-dismissible fade show m-3">
                                    <strong>Success:</strong> {{ Session::get('success_message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show m-3">
                                        <strong>Error:</strong> {!! $error !!}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Form Start --}}
                            <form method="POST" action="{{ route('states.update', $state->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row g-3 mb-4">
                                        {{-- Country Dropdown --}}
                                        <div class="col-md-4">
                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                            <select name="country_id" class="form-select" required>
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" {{ (old('country_id', $state->country_id) == $country->id) ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- State Name --}}
                                        <div class="col-md-4">
                                            <label class="form-label">State Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $state->name) }}" required>
                                        </div>
                                        {{-- State Code --}}
                                        <div class="col-md-4">
                                            <label class="form-label">State Code <span class="text-danger">*</span></label>
                                            <input type="text" name="state_code" class="form-control" value="{{ old('state_code', $state->state_code) }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Update State</button>
                                    <a href="{{ route('states.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                </div>
                            </form>
                            {{-- Form End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
