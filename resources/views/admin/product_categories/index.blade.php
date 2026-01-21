@extends('admin.layout.layout')
@section('title', 'Product Category | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Product Categories</h3>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ route('product-categories.create') }}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mb-0">Product Category List</h3>
                </div>

                <div class="card-body">


                    {{-- 📋 Table --}}
                    <div class="table-responsive">
                        <table id="category-table"
                               class="table table-bordered table-hover table-striped table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Category Name</th>
                                    <th>Status</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            @if($category->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('product-categories.edit', $category->id) }}"
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('product-categories.destroy', $category->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No categories found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    {{-- <div class="mt-3">
                        {{ $categories->withQueryString()->links() }}
                    </div> --}}

                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    let total = {{ $categories->count() }};
    if (total > 0) {
        $('#category-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50]
    });
    }
    
});
</script>
@endpush
