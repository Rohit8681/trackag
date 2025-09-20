@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">District List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">District Master</a></li>
                        <li class="breadcrumb-item active">District</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0">District List</h3>
                <a href="{{ route('districts.create') }}" class="btn btn-sm btn-primary ms-auto">
                    <i class="fas fa-plus me-1"></i> Add District
                </a>
            </div>

            <div class="card-body">
                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- 🔎 Filter Form --}}
                <form method="GET" action="{{ route('districts.index') }}" class="mb-3">
                    <div class="row g-2 align-items-end">
                        {{-- Country --}}
                        <div class="col-md-3">
                            <label for="country_id" class="form-label">Country</label>
                            <select name="country_id" id="country_id" class="form-select">
                                <option value="">-- Select Country --</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" 
                                        {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- State --}}
                        <div class="col-md-3">
                            <label for="state_id" class="form-label">State</label>
                            <select name="state_id" id="state_id" class="form-select">
                                <option value="">-- Select State --</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" 
                                        {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- District Name --}}
                        <div class="col-md-2">
                            <label for="name" class="form-label">District Name</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ request('name') }}" placeholder="Enter district name">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">-- Select Status --</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('districts.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Districts Table --}}
                <table id="districts-table" class="table table-bordered table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>District Name</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($districts as $index => $district)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $district->country->name ?? '-' }}</td>
                            <td>{{ $district->state->name ?? '-' }}</td>
                            <td>{{ $district->name }}</td>
                            <td>
                                @if($district->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('districts.edit', $district->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- <form action="{{ route('districts.destroy', $district->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure to delete this district?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form> --}}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No Districts Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
