@extends('admin.layout.layout')
@section('title', 'Order List | Trackag')

@section('content')
<main class="app-main">

    <!-- Page Header -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Order List</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Order Master</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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

                    <!-- Main Card -->
                    <div class="card card-primary card-outline">

                        <!-- Card Header -->
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Order Management</h3>

                            {{-- <a href="{{ route('order.create') }}" class="btn btn-primary ms-auto">
                                <i class="fas fa-plus me-1"></i> Add Order
                            </a> --}}
                        </div>

                        <div class="card-body">

                            <!-- Filter Section -->
                            <form method="GET" class="mb-3">
                                <div class="row g-2 align-items-end">

                                    <div class="col-md-2">
                                        <label class="form-label">State</label>
                                        <select name="state_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ request('state_id')==$state->id?'selected':'' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Sales Person</label>
                                        <select name="user_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ request('user_id')==$user->id?'selected':'' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Party</label>
                                        <select name="party_id" class="form-select">
                                            <option value="">All</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}" {{ request('party_id')==$c->id?'selected':'' }}>
                                                    {{ $c->agro_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Order Type</label>
                                        <select name="order_type" class="form-select">
                                            <option value="">All</option>
                                            <option value="cash" {{ request('order_type')=='cash'?'selected':'' }}>Cash</option>
                                            <option value="debit" {{ request('order_type')=='debit'?'selected':'' }}>Debit</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Product</label>
                                        <input type="text" name="product" value="{{ request('product') }}" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Packing</label>
                                        <input type="text" name="packing" value="{{ request('packing') }}" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Order No</label>
                                        <input type="text" name="order_no" value="{{ request('order_no') }}" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">All</option>
                                            <option>PENDING</option>
                                            <option>APPROVED</option>
                                            <option>REJECT</option>
                                            <option>DISPATCHED</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>

                                    <div class="col-md-1">
                                        <a href="{{ route('order.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-undo"></i>
                                        </a>
                                    </div>

                                </div>
                            </form>

                            <!-- Order Table -->
                            <div class="table-responsive">
                                <table id="order-table"
                                    class="table table-bordered table-hover table-striped align-middle table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40">ID</th>
                                            <th>Date</th>
                                            <th>State Name</th>
                                            <th>Order Type</th>
                                            <th>Order No</th>
                                            <th>Party</th>
                                            <th>Emp Name</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse($orders as $order)

                                            <tr>
                                                <td>
                                                    <button class="btn btn-sm btn-info toggle-items"
                                                        data-id="{{ $order->id }}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $order->user->state->name ?? '-' }}</td>
                                                <td>{{ $order->order_type }}</td>
                                                <td><strong>{{ $order->order_no }}</strong></td>
                                                <td>{{ $order->customer->agro_name ?? '-' }}</td>
                                                <td>{{ $order->user->name ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        ₹ {{ $order->items->sum('grand_total') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                            </tr>

                                            <!-- Expand Row -->
                                            <tr id="items-{{ $order->id }}" style="display:none; background:#f8f9fa;">
                                                <td colspan="9">
                                                    <table class="table table-bordered table-sm mb-0">
                                                        <thead class="table-secondary">
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Packing</th>
                                                                <th>Price</th>
                                                                <th>GST</th>
                                                                <th>Discount</th>
                                                                <th>Grand Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($order->items as $item)
                                                                <tr>
                                                                    <td>{{ $item->product->name ?? '-' }}</td>
                                                                    <td>{{ $item->packing->packing_size ?? '-' }}</td>
                                                                    <td>{{ $item->price }}</td>
                                                                    <td>{{ $item->gst }}</td>
                                                                    <td>{{ $item->discount }}</td>
                                                                    <td><strong>{{ $item->grand_total }}</strong></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">
                                                    No Orders Found
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

    var count = @json($orders->count());

    if(count > 0){
        $('#order-table').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5,10,25,50],
            columnDefs: [
                { orderable: false, targets: 0 }
            ]
        });
    }

    $(document).on('click','.toggle-items',function(){
        let id = $(this).data('id');
        $('#items-'+id).slideToggle();
        $(this).find('i').toggleClass('fa-plus fa-minus');
    });

});
</script>
@endpush