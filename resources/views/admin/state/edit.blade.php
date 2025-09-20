@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Edit State</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">State Master</a></li>
                        <li class="breadcrumb-item active">Edit State</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Update State</h3>
            </div>

            <div class="card-body">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
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
                <form method="POST" action="{{ route('states.update', $state->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        {{-- Country --}}
                        <div class="col-md-4">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <select name="country_id" class="form-select" required>
                                <option value="">-- Select Country --</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" 
                                        {{ old('country_id', $state->country_id) == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        {{-- State Name --}}
                        <div class="col-md-4">
                            <label class="form-label">State Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $state->name) }}" placeholder="Enter state name" required>
                            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>

                        {{-- State Code --}}
                        <div class="col-md-4">
                            <label class="form-label">State Code <span class="text-danger">*</span></label>
                            <input type="text" name="state_code" class="form-control" 
                                   value="{{ old('state_code', $state->state_code) }}" placeholder="Enter state code" required>
                            @error('state_code') <div class="text-danger">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-check mt-3">
                        <input type="checkbox" name="status" value="1" class="form-check-input"
                               {{ old('status', $state->status) ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update State</button>
                        <a href="{{ route('states.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
