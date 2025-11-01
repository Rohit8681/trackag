@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    <div class="app-content-header py-3 bg-light border-bottom">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h3 class="fw-bold mb-0 text-primary">
                        <i class="fas fa-users me-2 text-secondary"></i> Party Visit
                    </h3>
                    <p class="text-muted small mb-0">Track Daily and Monthly Party Visits</p>
                </div>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Party Visit</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="app-content py-4">
        <div class="container-fluid px-4">

            {{-- 🔍 Filters Section --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Financial Year</label>
                            <select class="form-select">
                                <option selected>2024-2025</option>
                                <option>2023-2024</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">State</label>
                            <select class="form-select">
                                <option>Gujarat</option>
                                <option>Maharashtra</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Employee Name</label>
                            <select class="form-select">
                                <option>Rohit Panchal</option>
                                <option>Vivek Patel</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Agro Name</label>
                            <select class="form-select">
                                <option>ABC Agro</option>
                                <option>XYZ Agro</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 🟡 Daily / Monthly Toggle --}}
            <div class="text-center mb-4">
                <button id="dailyBtn" class="btn btn-warning fw-bold px-4 me-2 active-mode">DAILY</button>
                <button id="monthlyBtn" class="btn btn-outline-warning fw-bold px-4">MONTHLY</button>
            </div>

            {{-- 📋 DAILY Table --}}
            <div id="dailyTable">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary fw-semibold"><i class="fas fa-calendar-day me-2"></i>Daily Visit List</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle text-nowrap">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Visited Date</th>
                                    <th>Employee Name</th>
                                    <th>Agro Name</th>
                                    <th>Check In - Out (Duration)</th>
                                    <th>Visit Purpose</th>
                                    <th>Follow-up Date</th>
                                    <th>Agro Visit Image</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>01-11-2025</td>
                                    <td>Rohit Panchal</td>
                                    <td>ABC Agro</td>
                                    <td>10:00 AM - 10:30 AM (30 min)</td>
                                    <td>New Order</td>
                                    <td>03-11-2025</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-image"></i> View
                                        </a>
                                    </td>
                                    <td>Good response</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>01-11-2025</td>
                                    <td>Vivek Patel</td>
                                    <td>XYZ Agro</td>
                                    <td>11:00 AM - 12:00 PM (1 hr)</td>
                                    <td>Payment Collection</td>
                                    <td>05-11-2025</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-image"></i> View
                                        </a>
                                    </td>
                                    <td>Collected ₹10,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 📅 MONTHLY Table --}}
            <div id="monthlyTable" class="d-none">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary fw-semibold"><i class="fas fa-calendar-alt me-2"></i>Monthly Visit Summary</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle text-nowrap">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Shop Name</th>
                                    <th>Employee Name</th>
                                    <th>Visit Count (Last Visit Date)</th>
                                    <th>Visit Purpose Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>ABC Agro</td>
                                    <td>Rohit Panchal</td>
                                    <td>5 (28-10-2025)</td>
                                    <td>
                                        <div>New Order - 2</div>
                                        <div>Payment Collection - 3</div>
                                        <div>Product Query - 2</div>
                                        <div>Complaint - 0</div>
                                        <div>Others - 0</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>XYZ Agro</td>
                                    <td>Vivek Patel</td>
                                    <td>3 (30-10-2025)</td>
                                    <td>
                                        <div>New Order - 1</div>
                                        <div>Payment Collection - 2</div>
                                        <div>Product Query - 1</div>
                                        <div>Complaint - 0</div>
                                        <div>Others - 0</div>
                                    </td>
                                </tr>
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
        // Toggle between Daily and Monthly
        $('#dailyBtn').click(function () {
            $('#dailyTable').removeClass('d-none');
            $('#monthlyTable').addClass('d-none');
            $('#dailyBtn').addClass('btn-warning text-dark fw-bold active-mode')
                          .removeClass('btn-outline-warning');
            $('#monthlyBtn').removeClass('btn-warning text-dark fw-bold active-mode')
                            .addClass('btn-outline-warning');
        });

        $('#monthlyBtn').click(function () {
            $('#dailyTable').addClass('d-none');
            $('#monthlyTable').removeClass('d-none');
            $('#monthlyBtn').addClass('btn-warning text-dark fw-bold active-mode')
                            .removeClass('btn-outline-warning');
            $('#dailyBtn').removeClass('btn-warning text-dark fw-bold active-mode')
                          .addClass('btn-outline-warning');
        });
    });
</script>
@endpush
