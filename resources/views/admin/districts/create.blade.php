@extends('admin.layout.layout')

@if(isset($district))
    @section('title', 'Edit District | Trackag')
@else
    @section('title', 'Create District | Trackag')
@endif

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0">District List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('districts.index') }}">District Master</a>
                        </li>
                        <li class="breadcrumb-item active">District</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-3">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    {{ isset($district) ? 'Edit District' : 'Add District' }}
                </h3>
            </div>

            <div class="card-body">
                <form action="{{ isset($district) ? route('districts.update',$district->id) : route('districts.store') }}" method="POST">
                    @csrf
                    @if(isset($district))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Country</label>
                            <select name="country_id" class="form-select" required>
                                <option value="">--Select Country--</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ old('country_id', $district->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State</label>
                            <select name="state_id" class="form-select" required>
                                <option value="">--Select State--</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}"
                                        {{ old('state_id', $district->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">District Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $district->name ?? '') }}"
                                   placeholder="Enter District Name" required>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 d-flex align-items-center mt-2">
                            <div class="form-check me-3">
                                <input type="checkbox" name="status" value="1" class="form-check-input"
                                       {{ old('status', $district->status ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">
                                {{ isset($district) ? 'Update' : 'Save' }}
                            </button>

                            <a href="{{ route('districts.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
