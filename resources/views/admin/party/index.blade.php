@extends('admin.layout.layout')

@section('content')
<main class="app-main">
    {{-- 🔷 Header --}}
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
                    <form id="filterForm" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Financial Year</label>
                            <select id="financialYear" class="form-select">
                                <option value="2024-2025" selected>2024-2025</option>
                                <option value="2023-2024">2023-2024</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" id="fromDate" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" id="toDate" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">State</label>
                            <select id="stateSelect" class="form-select">
                                <option value="">All</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Maharashtra">Maharashtra</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Employee Name</label>
                            <select id="employeeSelect" class="form-select">
                                <option value="">All</option>
                                {{-- <option value="Rohit Panchal">Rohit Panchal</option>
                                <option value="Vivek Patel">Vivek Patel</option> --}}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Agro Name</label>
                            <select id="agroSelect" class="form-select">
                                <option value="">All</option>
                                {{-- <option value="ABC Agro">ABC Agro</option>
                                <option value="XYZ Agro">XYZ Agro</option> --}}
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
                        <h5 class="mb-0 text-primary fw-semibold">
                            <i class="fas fa-calendar-day me-2"></i>Daily Visit List
                        </h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle text-nowrap" id="dailyTableData">
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
                            <tbody id="dailyData"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 📅 MONTHLY Table --}}
            <div id="monthlyTable" class="d-none">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary fw-semibold">
                            <i class="fas fa-calendar-alt me-2"></i>Monthly Visit Summary
                        </h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle text-nowrap" id="monthlyTableData">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Shop Name</th>
                                    <th>Employee Name</th>
                                    <th>Visit Count (Last Visit Date)</th>
                                    <th>Visit Purpose Count</th>
                                </tr>
                            </thead>
                            <tbody id="monthlyData"></tbody>
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

    let currentType = 'daily';
    loadPartyVisits(currentType);

    // 🔘 Toggle between Daily and Monthly
    $('#dailyBtn').click(function () {
        $('#dailyTable').removeClass('d-none');
        $('#monthlyTable').addClass('d-none');
        toggleActive($(this), $('#monthlyBtn'));
        currentType = 'daily';
        loadPartyVisits('daily');
    });

    $('#monthlyBtn').click(function () {
        $('#dailyTable').addClass('d-none');
        $('#monthlyTable').removeClass('d-none');
        toggleActive($(this), $('#dailyBtn'));
        currentType = 'monthly';
        loadPartyVisits('monthly');
    });

    function toggleActive(activeBtn, inactiveBtn) {
        activeBtn.addClass('btn-warning text-dark fw-bold active-mode').removeClass('btn-outline-warning');
        inactiveBtn.removeClass('btn-warning text-dark fw-bold active-mode').addClass('btn-outline-warning');
    }

    // 🔍 Reload on Filter Change
    $('#filterForm select, #fromDate, #toDate').on('change', function () {
        loadPartyVisits(currentType);
    });

    // 🧩 Load Data via AJAX
    function loadPartyVisits(type) {
        $.ajax({
            url: "{{ route('admin.get-party-visits') }}",
            type: 'GET',
            data: {
                type: type,
                user_id: $('#employeeSelect').val(),
                from_date: $('#fromDate').val(),
                to_date: $('#toDate').val(),
                state: $('#stateSelect').val(),
                agro_name: $('#agroSelect').val()
            },
            beforeSend: function() {
                if(type === 'daily') $('#dailyData').html('<tr><td colspan="9" class="text-center">Loading...</td></tr>');
                else $('#monthlyData').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
            },
            success: function(res) {
                if (res.success) {
                    if (type === 'daily') renderDaily(res.data);
                    else renderMonthly(res.data);
                }
            },
            error: function() {
                alert('Error loading data');
            }
        });
    }

    // 🧾 Render Daily Table
    function renderMonthly(data) {
        let html = '';
        const $table = $('#monthlyTableData');

        // 🧹 Destroy if already initialized
        if ($.fn.DataTable.isDataTable($table)) {
            $table.DataTable().clear().destroy();
        }

        if (!data || data.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted">No monthly data found</td></tr>';
            $('#monthlyTableData tbody').html(html);
            return; // 🚫 Stop here - don’t init DataTable
        }

        // ✅ Otherwise render rows
        data.forEach((item, index) => {
            let purposes = '';
            if (item.visit_purpose_count) {
                Object.entries(item.visit_purpose_count).forEach(([key, val]) => {
                    purposes += `<div>${key} - ${val}</div>`;
                });
            }

            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>${item.shop_name ?? '-'}</td>
                    <td>${item.employee_name ?? '-'}</td>
                    <td>${item.visit_count ?? 0} (${item.last_visit_date ?? '-'})</td>
                    <td>${purposes || '-'}</td>
                </tr>`;
        });

        $('#monthlyTableData tbody').html(html);

        // ✅ Initialize DataTable only when rows exist
        $table.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[3, 'desc']]
        });
    }


    // 🧾 Render Monthly Table
    function renderDaily(data) {
    let html = '';
    const $table = $('#dailyTableData');

    // 🧹 Destroy old DataTable instance if exists
    if ($.fn.DataTable.isDataTable($table)) {
        $table.DataTable().clear().destroy();
    }

    // 🕳️ If no data, show friendly message and skip init
    if (!data || data.length === 0) {
        html = '<tr><td colspan="9" class="text-center text-muted">No daily visits found</td></tr>';
        $('#dailyTableData tbody').html(html);
        return; // 🚫 Stop here - don’t init DataTable
    }

    // ✅ Build rows dynamically
    data.forEach((item, index) => {
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${item.visited_date ?? '-'}</td>
                <td>${item.employee_name ?? '-'}</td>
                <td>${item.agro_name ?? '-'}</td>
                <td>${item.check_in_out_duration ?? '-'}</td>
                <td>${item.visit_purpose ?? '-'}</td>
                <td>${item.followup_date ?? '-'}</td>
                <td class="text-center">
                    ${
                        item.agro_visit_image
                            ? `<a href="/storage/${item.agro_visit_image}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-image"></i> View
                              </a>`
                            : '-'
                    }
                </td>
                <td>${item.remarks ?? '-'}</td>
            </tr>`;
    });

    // 🧩 Replace tbody content
    $('#dailyTableData tbody').html(html);

    // 🚀 Initialize DataTable only when data exists
    $table.DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: -1 } // last column (remarks or image) not sortable
        ]
    });
}

});
</script>
@endpush
