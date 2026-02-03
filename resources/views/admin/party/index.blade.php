@extends('admin.layout.layout')
@push('styles')
<style>
/* ---------- TABLE DESIGN CLEAN ---------- */

.table-custom {
    border-collapse: separate !important;
    border-spacing: 0 8px !important;
    width: 100%;
}

.table-custom thead tr {
    background: #f7f9fc;
    color: #2a3f54;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.table-custom thead th {
    padding: 12px;
    text-align: center;
    font-size: 13px;
    border-bottom: 2px solid #dee2e6 !important;
}

.table-custom tbody tr {
    background: #ffffff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: 0.2s;
}

.table-custom tbody tr:hover {
    transform: scale(1.01);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.table-custom tbody td {
    padding: 14px 12px;
    vertical-align: middle !important;
    font-size: 14px;
    border-top: none !important;
}

.table-rounded {
    border-radius: 10px !important;
    overflow: hidden !important;
    border: 1px solid #e3e3e3 !important;
}
#imagePreviewModal .modal-body {
    display: flex;
    justify-content: center;
    align-items: center;
}

#imagePreviewModal img {
    width: 600px;          /* FIXED WIDTH */
    height: 400px;         /* FIXED HEIGHT */
    object-fit: contain;   /* image stretch nahi thay */
    background: #fff;
}
</style>
@endpush
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
                                <option value="2024-2025" selected>2025-2026</option>
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
                                <option value="all">All</option>
                                @foreach ($states as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Employee Name</label>
                            <select id="employeeSelect" class="form-select">
                                <option value="">All</option>
                                @foreach ($employees as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-2">
                            <label class="form-label fw-semibold">Agro Name</label>
                            <select id="agroSelect" class="form-select">
                                <option value="">All</option>
                                
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->agro_name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
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
                        <table class="table table-custom table-rounded" id="dailyTableData">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Visited Date</th>
                                    <th>Employee Name</th>
                                    <th>Agro Name</th>
                                    <th>Check In - Out (Duration)</th>
                                    <th>Visit Purpose</th>
                                    <th>Follow-up Date</th>
                                    <th>Image</th>
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
                        <table class="table table-custom table-rounded" id="monthlyTableData">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Shop Name</th>
                                    <th>Employee Name</th>
                                    <th>Visit Count</th>
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
    <!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Agro Visit Image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="previewImage" src="" class="img-fluid rounded" alt="Visit Image">
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

        if ($.fn.DataTable.isDataTable($table)) {
            $table.DataTable().clear().destroy();
        }

        if (!data || data.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted">No monthly data found</td></tr>';
            $('#monthlyTableData tbody').html(html);
            return;
        }

        data.forEach((item, index) => {

            // 🔥 FIXED PURPOSE DISPLAY
            let purposes = '';
            if (Array.isArray(item.visit_purpose_count)) {
                item.visit_purpose_count.forEach(p => {
                    purposes += `<div>${p.purpose_name} - ${p.count}</div>`;
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
                            ? `<a href="javascript:void(0)"
   class="btn btn-sm btn-outline-primary view-image"
   data-image="${item.agro_visit_image}">
   <i class="fas fa-image"></i> </a>`
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

$('#stateSelect').on('change', function () {
    let stateId = $(this).val();

    $.ajax({
        url: "{{ route('admin.getEmployeesByState') }}",
        type: "GET",
        data: { state_id: stateId },
        success: function (res) {
            let employeeSelect = $('#employeeSelect');
            employeeSelect.empty();
            employeeSelect.append('<option value="">All</option>');

            $.each(res, function (key, emp) {
                employeeSelect.append(
                    `<option value="${emp.id}">${emp.name}</option>`
                );
            });
        }
    });
});

$(document).on('click', '.view-image', function () {
    let imgUrl = $(this).data('image');
    $('#previewImage').attr('src', imgUrl);
    $('#imagePreviewModal').modal('show');
});
</script>
@endpush
