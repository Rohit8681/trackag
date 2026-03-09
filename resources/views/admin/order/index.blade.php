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
                    <div class="card-body border-bottom">

<form method="GET" action="{{ route('order.index') }}">

<div class="row g-2">

<div class="col-md-2">
<label>From Date</label>
<input type="date" name="from_date" class="form-control"
value="{{ request('from_date') }}">
</div>

<div class="col-md-2">
<label>To Date</label>
<input type="date" name="to_date" class="form-control"
value="{{ request('to_date') }}">
</div>

<div class="col-md-2">
<label>State</label>
<select name="state_id" class="form-control">
<option value="">All</option>
@foreach($states as $state)
<option value="{{ $state->id }}"
{{ request('state_id')==$state->id ? 'selected':'' }}>
{{ $state->name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label>Sales Person</label>
<select name="user_id" class="form-control">
<option value="">All</option>
@foreach($employees as $user)
<option value="{{ $user->id }}"
{{ request('user_id')==$user->id ? 'selected':'' }}>
{{ $user->name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label>Party</label>
<select name="party_id" class="form-control">
<option value="">All</option>
@foreach($customers as $c)
<option value="{{ $c->id }}"
{{ request('party_id')==$c->id ? 'selected':'' }}>
{{ $c->agro_name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label>Product</label>
<input type="text" name="product" class="form-control"
value="{{ request('product') }}">
</div>

<div class="col-md-2">
<label>Order Type</label>
<select name="order_type" class="form-control">
<option value="">All</option>
<option value="cash" {{ request('order_type')=='cash'?'selected':'' }}>Cash</option>
<option value="debit" {{ request('order_type')=='debit'?'selected':'' }}>Debit</option>
</select>
</div>

<div class="col-md-2">
<label>Order No</label>
<input type="text" name="order_no" class="form-control"
value="{{ request('order_no') }}">
</div>

<div class="col-md-2">
<label>Depo</label>
<select name="depo_id" class="form-control">
<option value="">All</option>
@foreach($depos as $depo)
<option value="{{ $depo->id }}"
{{ request('depo_id')==$depo->id ? 'selected':'' }}>
{{ $depo->name }}
</option>
@endforeach
</select>
</div>

<div class="col-md-2">
<label>Status</label>
<select name="status" class="form-control">
<option value="">All</option>
<option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
<option value="hold" {{ request('status')=='hold'?'selected':'' }}>Hold</option>
<option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
<option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
<option value="part_dispatched" {{ request('status')=='part_dispatched'?'selected':'' }}>Part Dispatched</option>
<option value="dispatched" {{ request('status')=='dispatched'?'selected':'' }}>Dispatched</option>
</select>
</div>

<div class="col-md-2 align-self-end">
<button class="btn btn-primary w-100">Filter</button>
</div>

<div class="col-md-2 align-self-end">
<a href="{{ route('order.index') }}" class="btn btn-secondary w-100">Reset</a>
</div>

</div>

</form>

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
                                                <button class="btn btn-sm btn-info view-items-btn" data-bs-toggle="modal" data-bs-target="#orderItemsModal" data-items="{{ json_encode($order->items->map(function($item) {
                                                    return [
                                                        'id'          => $item->id,
                                                        'product'     => optional($item->product)->product_name,
                                                        'packing_value' => optional($item->packing)->packing_value,
                                                        'packing'     => optional($item->packing)->packing_size,
                                                        'price'       => $item->price,
                                                        'gst'         => $item->gst,
                                                        'discount'    => $item->discount,
                                                        'qty'         => $item->qty,
                                                        'grand_total' => $item->grand_total
                                                    ];
                                                })) }}">
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

                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                                    <option value="hold" {{ $order->status == 'hold' ? 'selected' : '' }}>HOLD</option>
                                                    <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>APPROVED</option>
                                                    <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>REJECTED</option>
                                                    <option value="part_dispatched" {{ $order->status == 'part_dispatched' ? 'selected' : '' }}>PART DISPATCHED</option>
                                                    <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>DISPATCHED</option>

                                                </select>

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


    <!-- ORDER ITEMS MODAL -->
    <div class="modal fade" id="orderItemsModal" tabindex="-1" aria-labelledby="orderItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderItemsModalLabel">Order Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="modalItemsTable">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Product</th>
                                    <th>Packing</th>
                                    <th>Price</th>
                                    <th>GST (%)</th>
                                    <th>Discount</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


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

                        <input type="text" id="lr_number" name="lr_number" class="form-control mb-2">

                        <label>Transport Name</label>

                        <input type="text" id="transport_name" name="transport_name" class="form-control mb-2">

                        <label>Destination</label>

                        <input type="text" id="destination" name="destination" class="form-control mb-2">

                        <label>Dispatch Image <span class="text-danger">*</span></label>
                        <input type="file" id="dispatch_image" name="dispatch_image" class="form-control" accept="image/jpeg,image/png,image/jpg">

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
        pageLength: 10,
        ordering: false
    });

});


/* -----------------------
   ORDER ITEMS MODAL & EDIT
----------------------- */

$(document).on('click', '.view-items-btn', function (e) {
    e.preventDefault();
    let items = $(this).data('items');
    let tbody = $('#modalItemsBody');
    tbody.empty();

    if (items.length > 0) {
        $.each(items, function (index, item) {
            let row = `
            <tr data-id="${item.id}">
                <td>${item.product ? item.product : '-'}</td>
                <td>
${item.packing_value && item.packing ? item.packing_value + ' ' + item.packing : '-'}
</td>
                <td><input type="number" class="form-control form-control-sm edit-input item-price" value="${item.price}" disabled step="0.01" style="width: 80px;"></td>
                <td><input type="number" class="form-control form-control-sm edit-input item-gst" value="${item.gst}" disabled step="0.01" style="width: 70px;"></td>
                <td><input type="number" class="form-control form-control-sm edit-input item-discount" value="${item.discount}" disabled step="0.01" style="width: 80px;"></td>
                <td><input type="number" class="form-control form-control-sm edit-input item-qty" value="${item.qty}" disabled step="1" style="width: 70px;"></td>
                <td class="item-grand-total align-middle font-weight-bold">₹ ${parseFloat(item.grand_total).toFixed(2)}</td>
                <td class="align-middle">
                    <button class="btn btn-sm btn-primary edit-item-btn"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-success save-item-btn d-none"><i class="fas fa-save"></i></button>
                    <button class="btn btn-sm btn-secondary cancel-item-btn d-none"><i class="fas fa-times"></i></button>
                </td>
            </tr>`;
            tbody.append(row);
        });
    } else {
        tbody.append('<tr><td colspan="8" class="text-center">No items found.</td></tr>');
    }
});

$(document).on('click', '.edit-item-btn', function () {
    let tr = $(this).closest('tr');
    tr.find('.edit-input').prop('disabled', false);
    $(this).addClass('d-none');
    tr.find('.save-item-btn').removeClass('d-none');
    tr.find('.cancel-item-btn').removeClass('d-none');
    
    // Check if original data attributes are stored, if not, store them.
    if(typeof tr.data('orig-price') === "undefined") {
        tr.data('orig-price', tr.find('.item-price').val());
        tr.data('orig-gst', tr.find('.item-gst').val());
        tr.data('orig-discount', tr.find('.item-discount').val());
        tr.data('orig-qty', tr.find('.item-qty').val());
    }
});

$(document).on('click', '.cancel-item-btn', function () {
    let tr = $(this).closest('tr');
    tr.find('.edit-input').prop('disabled', true);
    
    // Restore original values
    tr.find('.item-price').val(tr.data('orig-price'));
    tr.find('.item-gst').val(tr.data('orig-gst'));
    tr.find('.item-discount').val(tr.data('orig-discount'));
    tr.find('.item-qty').val(tr.data('orig-qty'));
    
    tr.find('.save-item-btn').addClass('d-none');
    tr.find('.cancel-item-btn').addClass('d-none');
    tr.find('.edit-item-btn').removeClass('d-none');
});

$(document).on('click', '.save-item-btn', function () {
    let btn = $(this);
    let tr = btn.closest('tr');
    
    let itemId = tr.data('id');
    let price = tr.find('.item-price').val();
    let gst = tr.find('.item-gst').val();
    let discount = tr.find('.item-discount').val();
    let qty = tr.find('.item-qty').val();

    if (!price || !gst || !discount || !qty) {
        alert('All fields are required!');
        return;
    }

    let originalHtml = btn.html();

    $.ajax({
        url: "{{ route('order.item.update') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            item_id: itemId,
            price: price,
            gst: gst,
            discount: discount,
            qty: qty
        },
        beforeSend: function () {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        },
        success: function (res) {
            btn.prop('disabled', false).html(originalHtml);
            if (res.success) {
                // Update grand total
                tr.find('.item-grand-total').html('₹ ' + res.data.grand_total);
                tr.find('.edit-input').prop('disabled', true);
                
                // Update original data
                tr.data('orig-price', price);
                tr.data('orig-gst', gst);
                tr.data('orig-discount', discount);
                tr.data('orig-qty', qty);
                
                btn.addClass('d-none');
                tr.find('.cancel-item-btn').addClass('d-none');
                tr.find('.edit-item-btn').removeClass('d-none');
            } else {
                alert(res.message || 'Error occurred');
            }
        },
        error: function (xhr) {
            btn.prop('disabled', false).html(originalHtml);
            alert('An error occurred during update. Please try again.');
        }
    });
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


    if (status === 'hold' || status === 'rejected') {

        $('#remark_box').show();
        $('#statusModal').modal('show');

    }

    else if (status === 'part_dispatched' || status === 'dispatched') {

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

    let btn = $(this);
    let originalHtml = btn.html();

    let order_id = $('#modal_order_id').val();
    let status = $('.status-change[data-id="' + order_id + '"]').val();
    let remark = $('#remark').val();
    let lr_number = $('#lr_number').val();
    let transport_name = $('#transport_name').val();
    let destination = $('#destination').val();
    let dispatch_image = $('#dispatch_image')[0].files[0];

    let formData = new FormData();
    formData.append('_token', "{{ csrf_token() }}");
    formData.append('order_id', order_id);
    formData.append('status', status);
    formData.append('remark', remark);
    formData.append('lr_number', lr_number);
    formData.append('transport_name', transport_name);
    formData.append('destination', destination);

    if (dispatch_image) {
        formData.append('dispatch_image', dispatch_image);
    }

    $.ajax({
        url: "{{ route('order.status.update') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        },
        success: function (res) {
            btn.prop('disabled', false).html(originalHtml);
            if (res.status) {
                $('#statusModal').modal('hide');
                location.reload();
            } else {
                alert(res.message);
            }
        },
        error: function (xhr) {
            btn.prop('disabled', false).html(originalHtml);
            let errors = xhr.responseJSON.errors;
            if (errors) {
                let errorMsg = '';
                $.each(errors, function(key, value) {
                    errorMsg += value[0] + '\n';
                });
                alert(errorMsg);
            } else {
                alert('An error occurred while updating status.');
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