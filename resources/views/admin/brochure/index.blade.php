@extends('admin.layout.layout')
@section('title', 'Brochure List | Trackag')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Brochure List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Brochure Master</a></li>
                        <li class="breadcrumb-item active">Brochure</li>
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
                            <h3 class="card-title mb-0">Brochure List</h3>

                            {{-- @can('create_brochure') --}}
                            <a href="{{ route('brochure.create') }}" class="btn btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Brochure
                            </a>
                            {{-- @endcan --}}
                        </div>

                        <div class="card-body">

                            {{-- 🔍 Filter Form --}}
                            <form method="GET" action="{{ route('brochure.index') }}" class="mb-3">
                                <div class="row g-2 align-items-end">

                                    {{-- State --}}
                                    <div class="col-md-3">
                                        <label class="form-label">State</label>
                                        <select name="state_id" class="form-select">
                                            <option value="">-- Select State --</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}"
                                                    {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>

                                    <div class="col-md-1">
                                        <a href="{{ route('brochure.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            {{-- Table --}}
                            <div class="table-responsive" style="max-height: 600px;">
                                <table id="brochure-table" class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width:40px;">No</th>
                                            <th>Date</th>
                                            <th>State</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($brochures as $index => $brochure)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($brochure->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ $brochure->state->name ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ asset('storage/'.$brochure->pdf_path) }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-success">
                                                        <i class="fas fa-file-pdf me-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">
                                                    No brochures found.
                                                </td>
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
$(document).ready(function() {
    var brochures = @json($brochures->count());
    if (brochures > 0) {
        $('#brochure-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: -1 } // Actions column not orderable
            ]
        });
    }
});
</script>
@endpush
