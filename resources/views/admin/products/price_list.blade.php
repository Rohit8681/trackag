@extends('admin.layout.layout')
@section('title', 'Product Price List | Trackag')

@section('content')
<main class="app-main">

    {{-- Page Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Product Price List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">
                            <a href="#">Product Master</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Product Price List
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
                    <h3 class="card-title mb-0">State Wise Product Price</h3>
                </div>

                <form method="POST" action="{{ route('products.price.store') }}">
                    @csrf

                    <div class="card-body">
                        <div class="table-responsive" style="max-height:600px;">
                            <table class="table table-bordered table-hover table-sm align-middle">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th rowspan="2" style="width:40px;">No</th>
                                        <th rowspan="2">Product Name</th>
                                        <th rowspan="2">Packing Size</th>

                                        @foreach($states as $state)
                                            <th colspan="2" class="text-center">
                                                {{ $state->name }}
                                            </th>
                                        @endforeach
                                    </tr>

                                    <tr>
                                        @foreach($states as $state)
                                            <th class="text-center">Cash Price</th>
                                            <th class="text-center">Credit Price</th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>
                                    @php $sr = 1; @endphp

                                    @forelse($products as $product)
                                        @foreach($product->packings as $packing)

                                        <tr>
                                            <td>{{ $sr++ }}</td>

                                            <td>
                                                <strong>{{ $product->product_name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $product->technical_name }}
                                                </small>
                                            </td>

                                            <td>
                                                {{ $packing->packing_value }} {{ $packing->packing_size }}
                                            </td>

                                            <input type="hidden"
                                                   name="product_id[{{ $packing->id }}]"
                                                   value="{{ $product->id }}">

                                            @foreach($states as $state)
                                                @php
                                                    $price = $packing->prices
                                                        ->where('state_id', $state->id)
                                                        ->first();

                                                    $isAllowedState = $packing->packingStates
                                                    ->pluck('state_id')
                                                    ->contains($state->id);
                                                @endphp

                                                <td>
                                                    <input type="number" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value"
                                                        class="form-control form-control-sm text-end"
                                                        name="prices[{{ $packing->id }}][{{ $state->id }}][cash_price]"
                                                        value="{{ $price->cash_price ?? '' }}"
                                                        placeholder="0.00"
                                                        {{ $isAllowedState ? '' : 'readonly disabled' }}
                                                        style="{{ $isAllowedState ? '' : 'background:#f1f1f1;' }}">
                                                </td>

                                                <td>
                                                    <input type="number" min="0" step="0.01" oninput="this.value = this.value < 0 ? 0 : this.value"
                                                        class="form-control form-control-sm text-end"
                                                        name="prices[{{ $packing->id }}][{{ $state->id }}][credit_price]"
                                                        value="{{ $price->credit_price ?? '' }}"
                                                        placeholder="0.00"
                                                        {{ $isAllowedState ? '' : 'readonly disabled' }}
                                                        style="{{ $isAllowedState ? '' : 'background:#f1f1f1;' }}">
                                                </td>
                                            @endforeach
                                        </tr>

                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="{{ 3 + ($states->count() * 2) }}"
                                                class="text-center text-muted">
                                                No products found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Prices
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</main>
@endsection
