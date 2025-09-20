@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ isset($tehsil) ? 'Edit Tehsil' : 'Add Tehsil' }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Tehsil Master</a></li>
                        <li class="breadcrumb-item active">Tehsil</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ isset($tehsil) ? 'Edit Tehsil' : 'Add Tehsil' }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($tehsil) ? route('tehsils.update',$tehsil->id) : route('tehsils.store') }}" method="POST">
                    @csrf
                    @if(isset($tehsil))
                        @method('PUT')
                    @endif

                    {{-- Country --}}
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <select name="country_id" class="form-select" required>
                            <option value="">--Select Country--</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id', $tehsil->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    {{-- State --}}
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <select name="state_id" class="form-select" required>
                            <option value="">--Select State--</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}"
                                    {{ old('state_id', $tehsil->state_id ?? '') == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    {{-- District --}}
                    <div class="mb-3">
                        <label class="form-label">District</label>
                        <select name="district_id" class="form-select" required>
                            <option value="">--Select District--</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}"
                                    {{ old('district_id', $tehsil->district_id ?? '') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('district_id') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tehsil Name --}}
                    <div class="mb-3">
                        <label class="form-label">Tehsil Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $tehsil->name ?? '') }}" required>
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-check mb-3">
                        <input type="checkbox" name="status" value="1" class="form-check-input"
                               {{ old('status', $tehsil->status ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>

                    {{-- Buttons --}}
                    <button type="submit" class="btn btn-primary">
                        {{ isset($tehsil) ? 'Update' : 'Save' }}
                    </button>
                    <a href="{{ route('tehsils.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
