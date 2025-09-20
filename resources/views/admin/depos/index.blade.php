@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Depo List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Depo Master</a></li>
                        <li class="breadcrumb-item active">Depo</li>
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
                            <h3 class="card-title mb-0">Depo List</h3>
                            <a href="{{ route('depos.create') }}" class="btn btn-sm btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Depo
                            </a>
                        </div>

                        <div class="card-body">
                            {{-- ✅ Success Message --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- 🔎 Filter Form (optional) --}}
                            <form method="GET" action="{{ route('depos.index') }}" class="mb-3">
                                <div class="row g-2 align-items-end">
                                    {{-- Depo Code --}}
                                    <div class="col-md-3">
                                        <label for="depo_code" class="form-label">Depo Code</label>
                                        <input type="text" name="depo_code" id="depo_code" class="form-control"
                                            value="{{ request('depo_code') }}" placeholder="Enter depo code">
                                    </div>

                                    {{-- Depo Name --}}
                                    <div class="col-md-3">
                                        <label for="depo_name" class="form-label">Depo Name</label>
                                        <input type="text" name="depo_name" id="depo_name" class="form-control"
                                            value="{{ request('depo_name') }}" placeholder="Enter depo name">
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
                                        <a href="{{ route('depos.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-undo me-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            {{-- 📋 Depo Table --}}
                            <div class="table-responsive" style="max-height: 600px;">
                                <table class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th style="width: 40px;">No</th>
                                            <th>Depo Code</th>
                                            <th>Depo Name</th>
                                            <th>Manage By</th>
                                            <th>State</th>
                                            <th>District</th>
                                            <th>Tehsil</th>
                                            <th>City</th>
                                            <th>Status</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($depos as $index => $depo)
                                            <tr>
                                                <td>{{ $depos->firstItem() + $index }}</td>
                                                <td>{{ $depo->depo_code }}</td>
                                                <td>{{ $depo->depo_name }}</td>
                                                <td>{{ $depo->manage_by }}</td>
                                                <td>{{ $depo->state?->name ?? '-' }}</td>
                                                <td>{{ $depo->district?->name ?? '-' }}</td>
                                                <td>{{ $depo->tehsil?->name ?? '-' }}</td>
                                                <td>{{ $depo->city ?? '-' }}</td>
                                                <td>
                                                    @if($depo->status)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('depos.edit', $depo->id) }}"
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('depos.destroy', $depo->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure to delete this depo?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">No depos found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        {{-- Pagination --}}
                        <div class="card-footer clearfix">
                            {{ $depos->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection
