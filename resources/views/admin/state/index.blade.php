@extends('admin.layout.layout')

@section('content')
    <main class="app-main">
        <!-- Page Header -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">State List</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">State Master</a></li>
                            <li class="breadcrumb-item active">State</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-primary card-outline">
                            <div class="card-header d-flex align-items-center">
                                <h3 class="card-title mb-0">State List</h3>
                                <a href="{{ route('states.create') }}" class="btn btn-sm btn-primary ms-auto">
                                    <i class="fas fa-plus me-1"></i> Add State
                                </a>
                            </div>

                            <div class="card-body">
                                {{-- Success Message --}}
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                {{-- 🔎 Filter Form --}}
                                <form method="GET" action="{{ route('states.index') }}" class="mb-3">
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

                                        {{-- State Name --}}
                                        <div class="col-md-3">
                                            <label for="name" class="form-label">State Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                value="{{ request('name') }}" placeholder="Enter state name">
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-md-3">
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
                                                <i class="fas fa-search me-1"></i> 
                                            </button>
                                        </div>
                                        <div class="col-md-1">
                                            <a href="{{ route('states.index') }}" class="btn btn-secondary w-100">
                                                <i class="fas fa-undo me-1"></i> 
                                            </a>
                                        </div>
                                    </div>
                                </form>

                                {{-- States Table --}}
                                <div class="table-responsive" style="max-height: 600px;">
                                    <table id="states-table"
                                           class="table table-bordered table-hover table-striped align-middle table-sm">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th style="width: 40px;">No</th>
                                                <th>Country</th>
                                                <th>State Code</th>
                                                <th>State Name</th>
                                                <th>Status</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($states as $index => $state)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $state->country->name ?? '-' }}</td>
                                                    <td>{{ $state->state_code ?? '-' }}</td>
                                                    <td>{{ $state->name ?? '-' }}</td>
                                                    <td>
                                                        @if($state->status)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('states.edit', $state->id) }}" 
                                                           class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        {{-- Delete code (optional) --}}
                                                        {{-- <form action="{{ route('states.destroy', $state->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure to delete this state?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form> --}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No states found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
$(document).on('change', '.toggle-status', function () {
    let stateId = $(this).data('id');
    let status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('states.toggle-status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: stateId,
            status: status
        },
        success: function (response) {
            if (response.success) {
                toastr.success("Status changed to " + response.status);
            }
        },
        error: function () {
            toastr.error("Something went wrong!");
        }
    });
});
</script>
@endpush
