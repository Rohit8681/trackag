@extends('admin.layout.layout')
@section('title', 'Product List | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Product List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="#">Product Master</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Product List
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <div class="card card-primary card-outline">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title mb-0">Product List</h3>

                    <a href="{{ route('products.create') }}" class="btn btn-primary ms-auto">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="max-height:600px;">
                        <table id="product-table"
                               class="table table-bordered table-hover table-striped table-sm align-middle">

                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width:40px;">No</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Item Code</th>
                                    <th>Gross Weight (kg)</th>
                                    <th>Master Packing</th>
                                    <th>Status</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <strong>{{ $product->product_name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $product->technical_name }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $product->category->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $product->item_code ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $product->shipper_gross_weight ?? '-' }}
                                        </td>

                                        <td>
                                            @if($product->master_packing == "Yes")
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($product->status)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                               class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No products found.
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
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var total = @json($products->count());

    if (total > 0) {
        $('#product-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'asc']]
        });
    }
});
</script>
@endpush
