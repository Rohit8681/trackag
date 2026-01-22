@extends('admin.layout.layout')
@section('title', 'Crop Sub Categories | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Crop Sub Categories</h3>
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
                            <h3 class="card-title mb-0">Sub Category List</h3>
                            @if(auth()->user()->hasRole('master_admin'))
                            <a href="{{ route('crop-sub-categories.create') }}"
                               class="btn btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Sub Category
                            </a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="sub-category-table"
                                       class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;">No</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th style="width:120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($subCategories as $index => $sub)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $sub->category->name ?? '-' }}</td>
                                            <td>{{ $sub->name }}</td>
                                            <td>
                                                @if(auth()->user()->hasRole('master_admin'))
                                                <a href="{{ route('crop-sub-categories.edit',$sub->id) }}"
                                                   class="btn btn-sm btn-warning">
                                                    Edit
                                                </a>

                                                <form action="{{ route('crop-sub-categories.destroy',$sub->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this sub category?')">
                                                        Delete
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No sub categories found.
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
    $('#sub-category-table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50],
        ordering: true
    });
});
</script>
@endpush
