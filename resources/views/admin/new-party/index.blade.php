@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header py-3 bg-light border-bottom">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h3 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-store me-2 text-secondary"></i> New Party List
                    </h3>
                    <p class="text-muted small mb-0">Manage newly added parties and approval status</p>
                </div>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">New Party</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="app-content py-4">
        <div class="container-fluid px-4">

            {{-- 🔶 Filter Section (Yellow Header Style) --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3 bg-warning bg-opacity-25 rounded-3">
                    <form class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark">Financial Year</label>
                            <select class="form-select">
                                <option selected>2024-2025</option>
                                <option>2023-2024</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark">From Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark">To Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark">State</label>
                            <select class="form-select">
                                <option>Gujarat</option>
                                <option>Maharashtra</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark">Employee Name</label>
                            <select class="form-select">
                                <option>Rohit Panchal</option>
                                <option>Vivek Patel</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-dark">Agro Name</label>
                            <select class="form-select">
                                <option>ABC Agro</option>
                                <option>XYZ Agro</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 📋 New Party List Table --}}
            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle text-nowrap">
                        <thead class="text-center align-middle" style="background-color: #FFD966;">
                            <tr>
                                <th rowspan="2">Sr. No.</th>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Sales Person Name</th>
                                <th rowspan="2">Shop Name</th>
                                <th rowspan="2">Mobile No</th>
                                <th rowspan="2">Address</th>
                                <th rowspan="2">Contact Person</th>
                                <th rowspan="2">Working With</th>
                                <th rowspan="2">Party Document</th>
                                <th colspan="2">Status</th>
                                <th colspan="2">Approved</th>
                            </tr>
                            <tr>
                                <th>Reject</th>
                                <th>Hold</th>
                                <th>With Remarks</th>
                                <th>With Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td>01-11-2025</td>
                                <td>Rohit Panchal</td>
                                <td>ABC Agro</td>
                                <td>9876543210</td>
                                <td>Ahmedabad, Gujarat</td>
                                <td>Rajesh</td>
                                <td>Distributor</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-image me-1"></i> View (3)
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger">Reject</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning">Hold</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success">Approve</button>
                                </td>
                                <td>Verified by Admin</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td>30-10-2025</td>
                                <td>Vivek Patel</td>
                                <td>XYZ Agro</td>
                                <td>9998887770</td>
                                <td>Surat, Gujarat</td>
                                <td>Mahesh</td>
                                <td>Retailer</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-image me-1"></i> View (2)
                                    </button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger">Reject</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning">Hold</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-success">Approve</button>
                                </td>
                                <td>Pending Verification</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
