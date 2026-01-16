@extends('admin.layout.layout')
@section('title', 'Crop Categories | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Crop Categories</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Category List</h3>

                            <a href="{{ route('crop-categories.create') }}"
                               class="btn btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Category
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="category-table"
                                       class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;">No</th>
                                            <th>Category Name</th>
                                            <th style="width:120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($categories as $index => $category)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <a href="{{ route('crop-categories.edit',$category->id) }}"
                                                   class="btn btn-sm btn-warning">
                                                    Edit
                                                </a>

                                                <form action="{{ route('crop-categories.destroy',$category->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this category?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">
                                                No categories found.
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
$(document).ready(function () {
    $('#category-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50],
        ordering: true
    });
});
</script>
@endpush
