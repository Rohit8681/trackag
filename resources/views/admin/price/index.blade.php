@extends('admin.layout.layout')
@section('title', 'Price List | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Price List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Price Master</a></li>
                        <li class="breadcrumb-item active">Price List</li>
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
                    <h3 class="card-title mb-0">Price List</h3>

                    {{-- @can('create_price') --}}
                    <a href="{{ route('price.create') }}" class="btn btn-primary ms-auto">
                        <i class="fas fa-plus me-1"></i> Add Price List
                    </a>
                    {{-- @endcan --}}
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="max-height:600px;">
                        <table id="price-table" class="table table-bordered table-hover table-striped table-sm align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width:40px;">No</th>
                                    <th>Date</th>
                                    <th>State</th>
                                    <th>Price List</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($prices as $index => $price)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $price->created_at
                                                ->timezone('Asia/Kolkata')
                                                ->format('d-m-Y') }}
                                        </td>
                                        <td>{{ $price->state->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ asset('storage/'.$price->pdf_path) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-success">
                                                VIEW
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No price list found.
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
$(document).ready(function() {
    var prices = @json($prices->count());
    if (prices > 0) {
        $('#price-table').DataTable({
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