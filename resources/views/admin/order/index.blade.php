@extends('admin.layout.layout')

@section('title', 'Order | Trackag')

@section('content')
    <main class="app-main">

        {{-- Page Header --}}
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Order</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="app-content">
            <div class="container-fluid">

                {{-- FILTER SECTION --}}
                <div class="card mb-3 border-warning">
                    <div class="card-header bg-warning text-dark">
                        <strong>Filters</strong>
                    </div>
                    <div class="card-body">
                        <form method="GET">

                            <div class="row g-3">
                                <div class="col-md-2">
                                    <input type="date" name="from_date" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <input type="date" name="to_date" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <input type="text" name="order_no" class="form-control" placeholder="Order No">
                                </div>

                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option>PENDING</option>
                                        <option>EDIT</option>
                                        <option>HOLD</option>
                                        <option>APPROVED</option>
                                        <option>REJECT</option>
                                        <option>PART DISPATCHED</option>
                                        <option>DISPATCHED</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-dark w-100">Search</button>
                                </div>

                                <div class="col-md-2">
                                    <a href="{{ route('order.index') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>


                {{-- TABLE SECTION --}}
                <div class="card">
                    <div class="card-header">
                        <strong>Order List</strong>
                    </div>
                    <div class="card-body table-responsive">

                        <table class="table table-bordered table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Create Date</th>
                                    <th>State Name</th>
                                    <th>Order Type</th>
                                    <th>Order No</th>
                                    <th>Party Name</th>
                                    <th>Emp Name</th>
                                    <th>Amount</th>
                                    <th>Order Status</th>
                                    <th>Dispatch Date</th>
                                    <th width="160">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $order->user->state->name ?? '-' }}</td>
                                        <td>{{ $order->order_type }}</td>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->customer->agro_name ?? '-' }}</td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td>{{ $order->items->sum('total') }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $order->status }}</span>
                                        </td>
                                        <td>{{ $order->dispatch_date ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="changeStatus({{ $order->id }})">
                                                Change
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No Data Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>


                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        function changeStatus(id) {
            let status = prompt("Enter Status:\nPENDING\nEDIT\nHOLD\nAPPROVED\nREJECT\nPART DISPATCHED\nDISPATCHED");

            if (!status) return;

            $.ajax({
                url: "{{ route('order.status.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: id,
                    status: status
                },
                success: function (res) {
                    alert(res.message);
                    location.reload();
                }
            });
        }
    </script>
@endpush