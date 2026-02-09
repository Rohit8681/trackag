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
            <div class="card mb-3">
    <div class="card-header">
        <strong>Filters</strong>
    </div>
    <div class="card-body">

        {{-- ROW 1 --}}
        <div class="row g-3 mb-2">

            <div class="col-md-3">
                <label class="form-label">From Date</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">To Date</label>
                <input type="date" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">State</label>
                <select class="form-select">
                    <option>All States</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Emp Name</label>
                <select class="form-select">
                    <option>All Employees</option>
                </select>
            </div>

        </div>

        {{-- ROW 2 --}}
        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label">Party Name</label>
                <select class="form-select">
                    <option>All Parties</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Product</label>
                <select class="form-select">
                    <option>All Products</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Order Type</label>
                <select class="form-select">
                    <option>All</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Order No</label>
                <input type="text" class="form-control" placeholder="Enter Order No">
            </div>

            <div class="col-md-2">
                <label class="form-label">Order Status</label>
                <select class="form-select">
                    <option>All Status</option>
                    <option>PENDING</option>
                    <option>APPROVED</option>
                    <option>EDIT</option>
                    <option>HOLD</option>
                    <option>PART DISPATCHED</option>
                    <option>DISPATCHED</option>
                    <option>REJECT</option>
                </select>
            </div>

            <div class="col-md-1 d-flex gap-2">
                <button class="btn btn-primary w-100">Search</button>
                <button class="btn btn-secondary w-100">Reset</button>
            </div>

        </div>

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

        <tr>
            <td>10-02-2026</td>
            <td>Gujarat</td>
            <td>Online</td>
            <td>ORD-001</td>
            <td>ABC Traders</td>
            <td>Ramesh</td>
            <td>₹ 25,000</td>
            <td><span class="badge bg-warning">PENDING</span></td>
            <td>-</td>
            <td>
                <a href="#" class="btn btn-info btn-sm">View</a>
                <a href="#" class="btn btn-primary btn-sm">Edit</a>
                <a href="#" class="btn btn-success btn-sm">Approve</a>
                <a href="#" class="btn btn-danger btn-sm">Reject</a>
            </td>
        </tr>

        <tr>
            <td>11-02-2026</td>
            <td>Maharashtra</td>
            <td>Offline</td>
            <td>ORD-002</td>
            <td>XYZ Agro</td>
            <td>Suresh</td>
            <td>₹ 42,500</td>
            <td><span class="badge bg-success">APPROVED</span></td>
            <td>-</td>
            <td>
                <a href="#" class="btn btn-info btn-sm">View</a>
                <a href="#" class="btn btn-warning btn-sm">Hold</a>
                <a href="#" class="btn btn-primary btn-sm">Dispatch</a>
            </td>
        </tr>

        <tr>
            <td>12-02-2026</td>
            <td>Rajasthan</td>
            <td>Online</td>
            <td>ORD-003</td>
            <td>ABC Traders</td>
            <td>Mahesh</td>
            <td>₹ 18,000</td>
            <td><span class="badge bg-info">PART DISPATCHED</span></td>
            <td>13-02-2026</td>
            <td>
                <a href="#" class="btn btn-info btn-sm">View</a>
                <a href="#" class="btn btn-primary btn-sm">Dispatch</a>
            </td>
        </tr>

        <tr>
            <td>13-02-2026</td>
            <td>Gujarat</td>
            <td>Offline</td>
            <td>ORD-004</td>
            <td>XYZ Agro</td>
            <td>Ramesh</td>
            <td>₹ 30,000</td>
            <td><span class="badge bg-primary">DISPATCHED</span></td>
            <td>14-02-2026</td>
            <td>
                <a href="#" class="btn btn-info btn-sm">View</a>
            </td>
        </tr>

        <tr>
            <td>14-02-2026</td>
            <td>Gujarat</td>
            <td>Online</td>
            <td>ORD-005</td>
            <td>ABC Traders</td>
            <td>Suresh</td>
            <td>₹ 12,000</td>
            <td><span class="badge bg-danger">REJECT</span></td>
            <td>-</td>
            <td>
                <a href="#" class="btn btn-info btn-sm">View</a>
            </td>
        </tr>

    </tbody>
</table>


                </div>
            </div>

        </div>
    </div>
</main>
@endsection
