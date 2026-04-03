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
                                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                                </div>

                                <div class="col-md-2">
                                    <label>State</label>
                                    <select name="state_id" class="form-control">
                                        <option value="">All</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Sales Person</label>
                                    <select name="user_id" class="form-control">
                                        <option value="">All</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
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
                                            <option value="{{ $c->id }}" {{ request('party_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->agro_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Product</label>
                                    <input type="text" name="product" class="form-control" value="{{ request('product') }}">
                                </div>

                                <div class="col-md-2">
                                    <label>Order Type</label>
                                    <select name="order_type" class="form-control">
                                        <option value="">All</option>
                                        <option value="cash" {{ request('order_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="debit" {{ request('order_type') == 'debit' ? 'selected' : '' }}>Debit
                                        </option>
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
                                            <option value="{{ $depo->id }}" {{ request('depo_id') == $depo->id ? 'selected' : '' }}>
                                                {{ $depo->depo_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="hold" {{ request('status') == 'hold' ? 'selected' : '' }}>Hold</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                        <option value="part_dispatched" {{ request('status') == 'part_dispatched' ? 'selected' : '' }}>Part Dispatched</option>
                                        <option value="dispatched" {{ request('status') == 'dispatched' ? 'selected' : '' }}>
                                            Dispatched</option>
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
                                                                            <button class="btn btn-sm btn-info view-items-btn"
                                                                                data-id="{{ $order->id }}"
                                                                                data-status="{{ $order->status }}">
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

                                                                            <!-- <select class="form-select form-select-sm status-change"
                                                                                data-id="{{ $order->id }}">

                                                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                                                    PENDING</option>
                                                                                <option value="hold" {{ $order->status == 'hold' ? 'selected' : '' }}>HOLD
                                                                                </option>
                                                                                <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>
                                                                                    APPROVED</option>
                                                                                <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>
                                                                                    REJECTED</option>
                                                                                <option value="part_dispatched" {{ $order->status == 'part_dispatched' ? 'selected' : '' }}>PART DISPATCHED</option>
                                                                                <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>DISPATCHED</option>

                                                                            </select> -->

                                                                            <select class="form-select form-select-sm status-change"
    data-id="{{ $order->id }}"
    {{ $order->status == 'rejected' ? 'disabled' : '' }}>

    {{-- Pending --}}
    @if($order->status == 'pending')
        <option value="pending" selected>PENDING</option>
    @endif

    {{-- Hold --}}
    @if($order->status != 'approved')
        <option value="hold"
            {{ $order->status == 'hold' ? 'selected' : '' }}
            {{ $order->status != 'pending' ? 'disabled' : '' }}>
            HOLD
        </option>
    @endif

    {{-- Approved --}}
    <option value="approved"
        {{ $order->status == 'approved' ? 'selected' : '' }}
        {{ !in_array($order->status,['pending','hold']) ? 'disabled' : '' }}>
        APPROVED
    </option>

    {{-- Rejected --}}
    <option value="rejected"
        {{ $order->status == 'rejected' ? 'selected' : '' }}
        {{ $order->status != 'pending' ? 'disabled' : '' }}>
        REJECTED
    </option>

    {{-- Part Dispatch --}}
    <option value="part_dispatched"
        {{ $order->status == 'part_dispatched' ? 'selected' : '' }}
        {{ $order->status != 'approved' ? 'disabled' : '' }}>
        PART DISPATCHED
    </option>

    {{-- Dispatch --}}
    <option value="dispatched"
        {{ $order->status == 'dispatched' ? 'selected' : '' }}
        {{ $order->status != 'approved' ? 'disabled' : '' }}>
        DISPATCHED
    </option>

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
                                    <th>Shipper Size</th>
                                    <th>GST (%)</th>
                                    <th>Discount</th>
                                    <th>Qty</th>
                                    <th>Total</th>
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


    <!-- DISPATCH MODAL -->
    <div class="modal fade" id="dispatchModal" tabindex="-1" aria-labelledby="dispatchModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="dispatchModalLabel">Dispatch Management Panel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <form id="dispatchForm" enctype="multipart/form-data">
                        <input type="hidden" id="dispatch_order_id" name="order_id">
                        
                        <!-- Top LR Details Section -->
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold">Dispatch Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small fw-bold">LR Number</label>
                                        <input type="text" name="lr_number" id="dispatch_lr_number" class="form-control" placeholder="Enter LR No">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small fw-bold">Transport Name</label>
                                        <input type="text" name="transport_name" id="dispatch_transport_name" class="form-control" placeholder="Enter Transport">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small fw-bold">Vehicle Number</label>
                                        <input type="text" name="vehicle_no" id="dispatch_vehicle_no" class="form-control" placeholder="Enter Vehicle No">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted small fw-bold">Dispatch Image</label>
                                        <input type="file" name="dispatch_image" id="dispatch_img_upload" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold">Order Items</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0" id="dispatchItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Packing</th>
                                            <th>Price</th>
                                            <th>Shipper Size</th>
                                            <th>Order Qty</th>
                                            <th class="bg-primary text-white" style="width: 120px;">Dispatch Qty</th>
                                            <th>Pending Qty</th>
                                            <th>Total Price</th>
                                            <th>GST (%)</th>
                                            <th>Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dispatchItemsBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Previous Dispatches -->
                        <div class="card shadow-sm border-0" id="previousDispatchesCard" style="display:none;">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-history text-secondary me-2"></i>Dispatch History</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>LR Number</th>
                                                <th>Transport</th>
                                                <th>Vehicle</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody id="previousDispatchesBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning d-none text-dark fw-bold" id="btnPartDispatch">Part Dispatch</button>
                    <button type="button" class="btn btn-success d-none fw-bold" id="btnFullDispatch">Dispatch</button>
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
                        <input type="file" id="dispatch_image" name="dispatch_image" class="form-control"
                            accept="image/jpeg,image/png,image/jpg">

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
            let order_id = $(this).data('id');
            let status = $(this).data('status');
            console.log("View Items clicked! Order ID:", order_id, "Status:", status);
            
            $('#orderItemsModal').modal('show');
            let tbody = $('#modalItemsBody');
            tbody.html('<tr><td colspan="8" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i> Loading items...</td></tr>');

            $.ajax({
                url: `/admin/order/${order_id}/dispatch-data`,
                type: 'GET',
                success: function(res) {
                    if (res.status && res.items) {
                        tbody.empty();
                        let items = res.items;
                        if (items.length > 0) {
                            $.each(items, function (index, item) {
                                let packing = (item.packing_value && item.packing) ? item.packing_value + ' ' + item.packing : '-';
                                let row = `
                            <tr>
                                <td>${item.product ? item.product : '-'}</td>
                                <td>${packing}</td>
                                <td>₹${parseFloat(item.price).toFixed(2)}</td>
                                <td>${item.shipper_size ? item.shipper_size : '-'}</td>
                                <td>${item.gst}%</td>
                                <td>₹${parseFloat(item.discount).toFixed(2)}</td>
                                <td><span class="badge bg-secondary p-2 fs-6">${item.order_qty}</span></td>
                                <td class="align-middle fw-bold">₹${parseFloat(item.grand_total).toFixed(2)}</td>
                            </tr>`;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan="8" class="text-center">No items found.</td></tr>');
                        }
                    } else {
                        tbody.html('<tr><td colspan="8" class="text-center text-danger">Failed to load data.</td></tr>');
                    }
                },
                error: function() {
                    tbody.html('<tr><td colspan="8" class="text-center text-danger">Error loading details.</td></tr>');
                }
            });
        });

        // (Editing features removed per read-only requirement)

        /* -----------------------
           STATUS CHANGE
        ----------------------- */

        $(document).on('change', '.status-change', function () {

            let status = $(this).val();
            let order_id = $(this).data('id');

            console.log("status change", order_id, status);

            if (status === 'part_dispatched' || status === 'dispatched') {
                $(this).val($(this).find('option[selected]').val() || $(this).find('option:first').val());
                openDispatchModal(order_id);
                return;
            }

            $('#modal_order_id').val(order_id);
            $('#remark_box').hide();
            $('#dispatch_box').hide();

            if (status === 'hold' || status === 'rejected') {
                $('#remark_box').show();
                $('#statusModal').modal('show');
            } else {
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
                        $.each(errors, function (key, value) {
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
           DISPATCH MODAL & AJAX
        ----------------------- */

        function openDispatchModal(orderId) {
            console.log("Opening Dispatch Modal for Order ID:", orderId);
            $('#dispatch_order_id').val(orderId);
            $('#dispatchForm')[0].reset();
            $('#dispatchItemsBody').html('<tr><td colspan="10" class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i> Loading...</td></tr>');
            $('#previousDispatchesCard').hide();
            $('#btnPartDispatch, #btnFullDispatch').addClass('d-none');
            
            $('#dispatchModal').modal('show');

            console.log("Fetching dispatch data via AJAX...");
            $.ajax({
                url: `/admin/order/${orderId}/dispatch-data`,
                type: 'GET',
                success: function(res) {
                    console.log("Dispatch data response:", res);
                    if (res.status) {
                        renderDispatchItems(res.items);
                        renderDispatchHistory(res.dispatches, res.items);
                    } else {
                        alert('Failed to load dispatch data');
                        $('#dispatchModal').modal('hide');
                    }
                },
                error: function() {
                    alert('Error loading dispatch data');
                    $('#dispatchModal').modal('hide');
                }
            });
        }

        function renderDispatchItems(items) {
            let tbody = $('#dispatchItemsBody');
            tbody.empty();
            let totalPendingGlobally = 0;

            if (items.length > 0) {
                $.each(items, function (index, item) {
                    totalPendingGlobally += item.pending_qty;
                    let row = `
                    <tr data-id="${item.id}" data-price="${item.price}" data-gst="${item.gst}" data-discount="${item.discount}" data-pending="${item.pending_qty}">
                        <td class="fw-bold">${item.product || '-'}</td>
                        <td>${item.packing_value && item.packing ? item.packing_value + ' ' + item.packing : '-'}</td>
                        <td>₹${parseFloat(item.price).toFixed(2)}</td>
                        <td>${item.shipper_size || '-'}</td>
                        <td class="text-center"><span class="badge bg-secondary px-2 py-1">${item.order_qty}</span></td>
                        <td class="bg-primary bg-opacity-10 p-2">
                            <input type="number" class="form-control form-control-sm dispatch-qty-input fw-bold text-center border-primary shadow-sm" 
                                value="0" min="0" max="${item.pending_qty}" ${item.pending_qty === 0 ? 'disabled' : ''}>
                        </td>
                        <td class="text-center"><span class="badge bg-${item.pending_qty > 0 ? 'warning text-dark' : 'success'} px-2 py-1 item-pending-text">${item.pending_qty}</span></td>
                        <td class="item-total-price">₹0.00</td>
                        <td>${item.gst}%</td>
                        <td class="item-grand-total fw-bold text-success">₹0.00</td>
                    </tr>`;
                    tbody.append(row);
                });

                if (totalPendingGlobally > 0) {
                    $('#btnPartDispatch').removeClass('d-none');
                    $('#btnFullDispatch').removeClass('d-none');
                }
            } else {
                tbody.append('<tr><td colspan="10" class="text-center">No items found.</td></tr>');
            }
        }

        $(document).on('input', '.dispatch-qty-input', function() {
            let tr = $(this).closest('tr');
            let pendingQty = parseInt(tr.data('pending')) || 0;
            let qty = parseInt($(this).val()) || 0;

            if (qty > pendingQty) {
                qty = pendingQty;
                $(this).val(qty);
            }
            if (qty < 0) {
                qty = 0;
                $(this).val(qty);
            }

            let price = parseFloat(tr.data('price')) || 0;
            let gst = parseFloat(tr.data('gst')) || 0;
            let discount = parseFloat(tr.data('discount')) || 0;

            let total = price * qty;
            let totalAfterDiscount = total;
            if(qty > 0) {
                totalAfterDiscount = total - discount;
                if(totalAfterDiscount < 0) totalAfterDiscount = 0;
            } else {
                totalAfterDiscount = 0;
            }

            let gstAmount = (totalAfterDiscount * gst) / 100;
            let grandTotal = totalAfterDiscount + gstAmount;

            tr.find('.item-total-price').text('₹' + total.toFixed(2));
            tr.find('.item-grand-total').text('₹' + grandTotal.toFixed(2));
        });

        function renderDispatchHistory(dispatches, itemsMap) {
            let tbody = $('#previousDispatchesBody');
            tbody.empty();
            if (dispatches && dispatches.length > 0) {
                $('#previousDispatchesCard').show();
                let map = {};
                itemsMap.forEach(i => { map[i.id] = (i.product || 'Item') + ' (' + (i.packing || '') + ')'; });

                $.each(dispatches, function(index, d) {
                    let itemName = map[d.order_item_id] || 'Unknown';
                    let detail = d.detail || {};
                    let imgHtml = '-';
                    if (detail.dispatch_image) {
                        imgHtml = `<a href="/storage/${detail.dispatch_image}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-image"></i> View</a>`;
                    }
                    let dateStr = detail.dispatch_date ? detail.dispatch_date.substring(0, 10) : '-';
                    
                    let row = `
                    <tr>
                        <td>${dateStr}</td>
                        <td class="fw-bold">${itemName}</td>
                        <td><span class="badge bg-secondary font-monospace fs-6">${d.dispatch_qty}</span></td>
                        <td>${detail.lr_number || '-'}</td>
                        <td>${detail.transport_name || '-'}</td>
                        <td>${detail.vehicle_no || '-'}</td>
                        <td>${imgHtml}</td>
                    </tr>`;
                    tbody.append(row);
                });
            } else {
                $('#previousDispatchesCard').hide();
            }
        }

        $('#btnPartDispatch, #btnFullDispatch').on('click', function() {
            let btn = $(this);
            let form = $('#dispatchForm')[0];
            let formData = new FormData(form);

            let hasQty = false;
            let totalInputs = 0;
            $('#dispatchItemsBody tr').each(function(index, tr) {
                let itemId = $(tr).data('id');
                let qty = parseInt($(tr).find('.dispatch-qty-input').val()) || 0;
                if (qty > 0) {
                    hasQty = true;
                }
                formData.append(`dispatch_items[${totalInputs}][item_id]`, itemId);
                formData.append(`dispatch_items[${totalInputs}][dispatch_qty]`, qty);
                totalInputs++;
            });

            if (!hasQty) {
                alert('Please enter dispatch quantity for at least one item.');
                return;
            }

            let originalHtml = btn.html();
            
            $.ajax({
                url: "{{ route('order.dispatch.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btnPartDispatch, #btnFullDispatch').prop('disabled', true);
                    btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                },
                success: function(res) {
                    if (res.status) {
                        $('#dispatchModal').modal('hide');
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let eMsg = '';
                        $.each(errors, function(k, v) { eMsg += v[0] + '\\n'; });
                        alert(eMsg);
                    } else {
                        alert('Failed to save dispatch.');
                    }
                },
                complete: function() {
                    $('#btnPartDispatch, #btnFullDispatch').prop('disabled', false);
                    btn.html(originalHtml);
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