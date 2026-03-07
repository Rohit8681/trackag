@extends('admin.layout.layout')
@section('title', 'Order List | Trackag')

@section('content')

    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-sm-6">
                        <h3 class="mb-0">Order List</h3>
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item">Order Master</li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                <div class="card card-primary card-outline">

                    <div class="card-header">
                        <h3 class="card-title">Order Management</h3>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="order-table" class="table table-bordered table-hover table-striped">

                                <thead>
                                    <tr>
                                        <th width="40"></th>
                                        <th>Date</th>
                                        <th>State</th>
                                        <th>Order Type</th>
                                        <th>Order No</th>
                                        <th>Party</th>
                                        <th>Employee</th>
                                        <th>Amount</th>
                                        <th width="160">Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach($orders as $order)

                                        <tr>

                                            <td>
                                                <button class="btn btn-sm btn-info toggle-items" data-id="{{ $order->id }}">
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

                                                <select class="form-select form-select-sm status-change"
                                                    data-id="{{ $order->id }}">

                                                    <option {{ $order->status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                                    <option {{ $order->status == 'EDIT' ? 'selected' : '' }}>EDIT</option>
                                                    <option {{ $order->status == 'HOLD' ? 'selected' : '' }}>HOLD</option>
                                                    <option {{ $order->status == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                                                    <option {{ $order->status == 'REJECT' ? 'selected' : '' }}>REJECT</option>
                                                    <option {{ $order->status == 'PART DISPATCHED' ? 'selected' : '' }}>PART
                                                        DISPATCHED</option>
                                                    <option {{ $order->status == 'DISPATCHED' ? 'selected' : '' }}>DISPATCHED</option>

                                                </select>

                                            </td>

                                        </tr>


                                        <tr id="items-{{ $order->id }}" style="display:none;background:#f9f9f9">

                                            <td colspan="9">

                                                <table class="table table-bordered table-sm mb-0">

                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Packing</th>
                                                            <th>Price</th>
                                                            <th>GST</th>
                                                            <th>Discount</th>
                                                            <th>Qty</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        @foreach($order->items as $item)

                                                            <tr>

                                                                <td>{{ $item->product->product_name ?? '-' }}</td>

                                                                <td>{{ $item->packing->packing_size ?? '-' }}</td>

                                                                <td>{{ $item->price }}</td>

                                                                <td>{{ $item->gst }}</td>

                                                                <td>{{ $item->discount }}</td>

                                                                <td>{{ $item->qty }}</td>

                                                                <td>{{ $item->grand_total }}</td>

                                                            </tr>

                                                        @endforeach

                                                    </tbody>

                                                </table>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>


    <!-- STATUS MODAL -->

    <div class="modal fade" id="statusModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Update Order Status</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="modal_order_id">

                    <div id="remark_box" style="display:none">

                        <label>Remark</label>

                        <textarea id="remark" class="form-control"></textarea>

                    </div>

                    <div id="dispatch_box" style="display:none">

                        <label>LR Number</label>

                        <input type="text" id="lr_number" class="form-control mb-2">

                        <label>Transport Name</label>

                        <input type="text" id="transport_name" class="form-control mb-2">

                        <label>Destination</label>

                        <input type="text" id="destination" class="form-control">

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-primary" id="saveStatus">
                        Update
                    </button>

                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')
<script>

$(function () {

    $('#order-table').DataTable({
        responsive: true,
        pageLength: 10
    });

});


/* -----------------------
   PLUS ICON TOGGLE
----------------------- */

$(document).on('click', '.toggle-items', function (e) {

    e.preventDefault();

    let id = $(this).data('id');

    console.log("clicked id:", id);

    $('#items-' + id).toggle();

    $(this).find('i').toggleClass('fa-plus fa-minus');

});


/* -----------------------
   STATUS CHANGE
----------------------- */

$(document).on('change', '.status-change', function () {

    let status = $(this).val();
    let order_id = $(this).data('id');

    console.log("status change", order_id, status);

    $('#modal_order_id').val(order_id);

    $('#remark_box').hide();
    $('#dispatch_box').hide();


    if (status === 'HOLD' || status === 'REJECT') {

        $('#remark_box').show();
        $('#statusModal').modal('show');

    }

    else if (status === 'PART DISPATCHED' || status === 'DISPATCHED') {

        $('#dispatch_box').show();
        $('#statusModal').modal('show');

    }

    else {

        updateStatus(order_id, status);

    }

});


/* -----------------------
   MODAL SAVE BUTTON
----------------------- */

$(document).on('click', '#saveStatus', function () {

    let order_id = $('#modal_order_id').val();

    let status = $('.status-change[data-id="' + order_id + '"]').val();

    let remark = $('#remark').val();

    let lr_number = $('#lr_number').val();

    let transport_name = $('#transport_name').val();

    let destination = $('#destination').val();


    $.ajax({

        url: "{{ route('order.status.update') }}",

        type: "POST",

        data: {

            _token: "{{ csrf_token() }}",

            order_id: order_id,

            status: status,

            remark: remark,

            lr_number: lr_number,

            transport_name: transport_name,

            destination: destination

        },

        success: function (res) {

            if (res.status) {

                location.reload();

            }

        }

    });

});


/* -----------------------
   SIMPLE STATUS UPDATE
----------------------- */

function updateStatus(order_id, status) {

    $.ajax({

        url: "{{ route('order.status.update') }}",

        type: "POST",

        data: {

            _token: "{{ csrf_token() }}",

            order_id: order_id,

            status: status

        },

        success: function (res) {

            if (res.status) {

                location.reload();

            }

        }

    });

}

</script>

@endpush